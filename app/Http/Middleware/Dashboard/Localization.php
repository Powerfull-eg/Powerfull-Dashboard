<?php

namespace App\Http\Middleware\Dashboard;

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
        $fallback = Arr::first(config('app.dashboard_locales'));
        $locale = session()->get('dashboard_locale', $fallback);

        if (! in_array($locale, config('app.dashboard_locales'))) {
            $locale = $fallback;
        }

        session()->put('dashboard_locale', $locale);
        app()->setLocale(session()->get('dashboard_locale'));

        return $next($request);
    }
}
