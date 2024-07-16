<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\ProfileUpdateRequest;
use App\Traits\CanUploadFile;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use CanUploadFile;

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('dashboard.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        if ($request->hasFile('profile_picture')) {
            if ($old = $user->profile_picture) {
                $this->deleteFileFromUrl($old);
            }

            $user->profile_picture = $this->uploadFile($request->file('profile_picture'), 'dashboard/profile_pictures');
        }

        $user->save();

        return redirect()->route('dashboard.profile.edit')->with('success', __(':resource has been updated.', ['resource' => __('Profile')]));
    }
}
