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