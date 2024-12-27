<?php

if (! function_exists('setting')) {
    /**
     * Get the specified setting value.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return \App\Models\Setting::get($key, $default);
    }
}

// Convert Chinese Date to Cairo Date
if (! function_exists('chineseToCairoTime')) {
    function chineseToCairoTime($time) {
        return Illuminate\Support\Carbon::parse($time)->subHours(6)->format('Y-m-d H:i:s');
    }
}

// Convert Seconds To Time String
if (! function_exists('secondsToTimeString')) {
    function secondsToTimeString($time) {
    // change seconds to string
        $hours = floor($time / 3600);
        $minutes = floor(($time - ($hours * 3600)) / 60);
        $seconds = $time - ($hours * 3600) - ($minutes * 60);
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}