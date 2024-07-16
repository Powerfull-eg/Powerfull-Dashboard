<?php

namespace App\Http\Controllers;

class TranslationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function show(string $locale)
    {
        $path = lang_path($locale . '.json');
        $translations = file_exists($path) ? file_get_contents($path) : '{}';

        $javascriptFile = "window.__locale = '" . $locale . "';\n";
        $javascriptFile .= "window.__translations = " . $translations . ";\n";

        return response($javascriptFile)->header('Content-Type', 'application/javascript');
    }
}
