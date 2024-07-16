<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Show the impersonate form.
     */
    public function create()
    {
        $admins = Admin::whereNotCurrentAdmin()->get()->pluck('name', 'id');

        return view('dashboard.impersonate.create', [
            'admins' => $admins,
        ]);
    }

    /**
     * Impersonate the given user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'admin_id' => ['required', 'exists:admins,id'],
        ]);

        Auth::guard('admins')->login(Admin::find($request->admin_id));

        toastify()->success(__('Impersonating :name', ['name' => Auth::user()->name]));

        return redirect()->route('dashboard.index');
    }
}
