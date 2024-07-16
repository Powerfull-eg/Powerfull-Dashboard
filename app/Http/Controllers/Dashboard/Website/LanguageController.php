<?php

namespace App\Http\Controllers\Website;

class LanguageController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $locale)
    {
        $locale = strtolower($locale);

        if (! in_array($locale, config('app.website_locales'))) {
            return abort(404);
        }

        app()->setLocale($locale);
        session()->put('website_locale', $locale);

        return redirect()->back();
    }
}
