<?php

namespace App\Http\Middleware;

use App\Settings\System\GeneralSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckForMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*') || $request->is('admin') || $request->is('livewire*')) {
            return $next($request);
        }

        if (app(GeneralSettings::class)->is_maintenance) {
            abort(503);
        }

        return $next($request);
    }
}
