<?php

namespace App\Http\Middleware\Website;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fallback = Arr::first(config('app.website_locales'));
        $locale = session()->get('website_locale', $fallback);

        if (! in_array($locale, config('app.website_locales'))) {
            $locale = $fallback;
        }

        session()->put('website_locale', $locale);
        app()->setLocale(session()->get('website_locale'));

        return $next($request);
    }
}
