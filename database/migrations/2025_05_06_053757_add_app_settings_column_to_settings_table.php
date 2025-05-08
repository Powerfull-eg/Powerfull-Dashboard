<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('app_settings')->default(false)->after('value');
        });
        $data = [
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
            ];
        foreach ($data as $key => $value) {
            $value = is_array($value) ? json_encode($value) : $value;
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'app_settings' => true],
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('app_settings');
        });
    }
};
