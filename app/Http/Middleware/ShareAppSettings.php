<?php

namespace App\Http\Middleware;

use App\Support\Settings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ShareAppSettings
{
    public function handle(Request $request, Closure $next)
    {
        View::share('appSettings', Settings::all());

        return $next($request);
    }
}
