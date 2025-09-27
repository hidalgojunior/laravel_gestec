<?php

namespace App\Http\Middleware;

use App\Models\GlobalSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = GlobalSetting::getSettings();

        // Se estiver em modo de manutenção e o usuário não for admin
        if ($settings->isMaintenanceMode() && (!Auth::check() || !Auth::user()->isAdmin())) {
            return response()->view('maintenance', [
                'message' => $settings->maintenance_message,
                'settings' => $settings,
            ], 503); // Service Unavailable
        }

        return $next($request);
    }
}
