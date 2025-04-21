<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function index(){
        return response()->json([
            "map" => [
                    "lat" => 30.222656,
                    "lng" => 31.477425,
                    "zoom" => 10,
                    "mapId" => "a55a8dd1e435899e"
    
                ],
            "maintenance" => false,
            'timezone' => 'Africa/Cairo',
              'appAndroidLink'=> 'https://play.google.com/store/apps/details?id=com.powerfull.app',
            'appIosLink' => 'https://apps.apple.com/eg/app/powerfull/id6477441692',
            'appIosVersion' => '1.1.0',
            'updateIosMandatory' => '1.1.0',
            'appAndroidVersion' => '1.1.5',
            'updateAndroidMandatory' => '1.1.4',
            'enUpdateTitle' => 'New Update Available',
            'enUpdateMessage' => "We've made some exciting improvements to enhance your experience. Update now to enjoy the latest features and performance upgrades!",
              'arUpdateTitle' => "!تحديث جديد",
            'arUpdateMessage' => "لقد أجرينا بعض التحسينات الرائعة لتعزيز تجربتك. قم بالتحديث الآن للاستمتاع بأحدث الميزات وتحسينات الأداء",
            'oauth' => [
                'active' => true,
                'platforms' => [
                    'google',
                    'facebook',
                    'twitter'
                ]
            ]
            ]);
    }
}
