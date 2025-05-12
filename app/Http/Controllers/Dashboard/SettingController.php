<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SettingController extends Controller
{
    private $oauthPlatforms = [
        'facebook',
        'google',
        'twitter'
    ];

    private $app_settings;
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $payment_gateways = [
            'paymob' => 'Paymob',
            'fawry' => 'Fawry'
        ];
        // exist platforms 
        $platforms['platforms']  = $this->oauthPlatforms;
        foreach ( $platforms['platforms'] as $platform) {
            $platforms['active'][$platform] = in_array($platform, json_decode(setting('oauth'),true)['platforms']);
        }

        return view('dashboard.settings.edit', [
            'payment_gateways' => $payment_gateways,
            'oauthPlatforms' => $platforms
        ]);
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
            'payment_gateway' => 'required|string|max:255',
        ]);

        // Add App settings
        $this->updateAppSettings($request);
        $settings = array_merge($settings, $this->app_settings);

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

    /**
     * Update Application Settings.
     */
    private function updateAppSettings(Request $request)
    {
        $this->app_settings = $request->validate([
            'bundle_id' => 'required|string',
            'map_lat' => 'required|numeric',
            'map_lng' => 'required|numeric',
            'map_zoom' => 'required|numeric',
            'map_mapId' => 'required|string',
            'appAndroidLink' => 'required|string',
            'appAndroidVersion' => 'required|string',
            'updateAndroidMandatory' => 'required|string',
            'appIosLink' => 'required|string',
            'appIosVersion' => 'required|string',
            'updateIosMandatory' => 'required|string',
            'timezone' => 'required|string',
            'enUpdateTitle' => 'required|string',
            'enUpdateMessage' => 'required|string',
            'arUpdateTitle' => 'required|string',
            'arUpdateMessage' => 'required|string',
            'maintenance' => 'string',
            'otp' => 'string',
            'oauth_active' => 'string',
            'oauth_platforms_facebook' => 'string',
            'oauth_platforms_google' => 'string',
            'oauth_platforms_twitter' => 'string',
        ]);

        $this->app_settings['maintenance'] = isset($this->app_settings['maintenance']) ? true : false;
        $this->app_settings['otp'] = isset($this->app_settings['otp']) ? true : false;
        $this->handleOauthPlatforms();
        $this->handleMapSettings();
    }

    private function handleOauthPlatforms() {
        $inputs = $this->app_settings;
        $platforms = $this->oauthPlatforms;
        $selected = [];
        foreach ($platforms as $platform) {
            if(Arr::has($inputs,"oauth_platforms_$platform")) {
                $selected[] = $platform;
                unset($this->app_settings["oauth_platforms_$platform"]);
            }
        }
        $active = isset($inputs["oauth_active"]) ? true : false;
        unset($this->app_settings["oauth_active"]);

        $this->app_settings['oauth'] = ["active" => $active ,"platforms" => $selected];
    }

    // Handle Map Settings
    private function handleMapSettings() {
        foreach($this->app_settings as $i => $v) {
            if(str_contains($i,"map_")) {
                $key = explode("_", $i);
                $this->app_settings[$key[0]][$key[1]] = $this->app_settings[$i];
                unset($this->app_settings[$i]);
            }
        }
    }
}
