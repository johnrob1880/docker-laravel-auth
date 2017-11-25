<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Closure;
use Config;

class DetectLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hostname = parse_url(strtolower($request->url()), PHP_URL_HOST);
        $url_array = explode('.', $hostname);
        $location = $url_array[0];
        $locations = Config::get('app.locations');
                

        if (false !== strpos($location, '-'))
        {
            $locales = Config::get('app.locales');
            list($location, $locale) = explode('-', $location);

            if ($locale && array_key_exists($locale, $locales))
            {
                Config::set('app.locations.' . $location . '.default_locale', $locale);
            }
        }
   
        if (array_key_exists($location, $locations)) {
            if (Config::get('app.location') != $location)
            {
                Config::set('app.location', $location);
                Event::dispatch(new \App\Events\LocationUpdated($location));           
            }

            return $next($request);
        }

        return $next($request);
    }
}
