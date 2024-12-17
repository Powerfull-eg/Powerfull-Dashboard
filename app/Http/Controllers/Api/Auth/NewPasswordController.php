<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('website.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => 'required',
            'phone' => 'required_without:email|numeric',
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()],
        ]);
        
        // remove start zero 
        if($validated['phone']){
            $validated['phone'] = Str::startsWith($validated['phone'],0) ? Str::substr($validated['phone'],1) : $validated['phone'];
        }
        $validated['password_confirmation'] = $request->password_confirmation;

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.

        $status = Password::broker('users')->reset(
            $validated,
            function (User $user,string $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.

        $message = __($status);
        $status = $status === Password::PASSWORD_RESET ? 'success' : 'error';
        return redirect()->route('website.password.reset-result', ['status' => $status, 'message' => $message]);
    }

    /**
     * Display the password reset view.
     */
    public function resetResult(): View
    {
        return view('website.auth.reset-result');
    }
}
