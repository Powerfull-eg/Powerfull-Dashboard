<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = array_unique([...config('app.website_locales'), ...config('app.dashboard_locales')]);

        return view('dashboard.language.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $locale)
    {
        if (! File::exists(lang_path($locale . '.json'))) {
            return redirect()->route('dashboard.language.index')->with('error', __('The language file does not exist!'));
        }

        $translations = json_decode(File::get(lang_path($locale . '.json')));

        return view('dashboard.language.edit', [
            'locale' => $locale,
            'translations' => $translations,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $locale)
    {
        $locale = strtolower($locale);

        $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        File::put(lang_path($locale . '.json'), json_encode($request->get('translations'), $flags));

        return redirect()->route('dashboard.language.index')->with('success', __(':resource has been updated.', ['resource' => __('Language')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale)
    {
        $locale = strtolower($locale);

        if (! in_array($locale, config('app.dashboard_locales'))) {
            return abort(404);
        }

        app()->setLocale($locale);
        session()->put('dashboard_locale', $locale);

        return redirect()->back();
    }
}
