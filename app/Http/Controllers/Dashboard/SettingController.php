<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('dashboard.settings.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $settings = $request->validate([
            'app_name' => 'required|string|max:255',
            'google_analytics_property_id' => 'nullable|string|max:255',
            'facebook_pixel_id' => 'nullable|string|max:255',
        ]);

        $settings['page_loader_enabled'] = $request->has('page_loader_enabled');

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('dashboard.settings.edit')
            ->with('success', __(':resource has been updated.', ['resource' => __('Settings')]));
    }
}
