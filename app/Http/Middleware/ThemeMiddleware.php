<?php

namespace App\Http\Middleware;

use App\Models\GlobalSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = GlobalSetting::getSettings();
        $theme = $settings->theme ?? 'light';

        // Compartilhar o tema com todas as views
        view()->share('currentTheme', $theme);

        return $next($request);
    }
}
