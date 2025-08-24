<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocaleFromHeader
{
    public function handle($request, Closure $next)
    {
        $locale = $request->header('Accept-Language');

        if (in_array($locale, ['ar', 'en'])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
