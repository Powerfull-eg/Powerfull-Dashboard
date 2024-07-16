<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Route names to redirect to if the user is authenticated.
     *
     * @var array<string, string>
     */
    protected array $redirectTo = [
        'admins' => RouteServiceProvider::DASHBOARD,
        'users' => RouteServiceProvider::HOME,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $route = $this->redirectTo[$guard] ?? RouteServiceProvider::HOME;

                return redirect()->to($route);
            }
        }

        return $next($request);
    }
}
