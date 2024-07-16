<?php

namespace App\Http\Controllers\Dashboard;

use Redot\LaravelLangExtractor\LangExtractor;

class SyncLanguageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function update(string $locale)
    {
        $path = lang_path($locale . '.json');

        $extractor = new LangExtractor();
        $extractor->extract()->mergeWithFile($path)->save($path, true);

        return redirect()->route('dashboard.language.index')->with('success', __(':resource has been updated.', ['resource' => __('Language')]));
    }
}
