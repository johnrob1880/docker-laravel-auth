<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Config;
use Event;

class DetectLocale
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
        
        $origin = $request->origin;
        $locale = $request->locale;

        if (is_null($origin))
        {
            $origin = Config::get('app.fallback_origin');
        }

        if (in_array($origin, Config::get('app.origins')))
        {
           
            if (Config::get('app.origin') != $origin)
            {
                Config::set('origin', $origin); 
                Event::dispatch(new \App\Events\LocationUpdated($origin));            
            }
        }

        $locales = Config::get('app.locales');

        if (is_null($locale))
        {
            // default browser language
            $language = strtolower(substr($request->server->get('HTTP_ACCEPT_LANGUAGE'), 0, 2));

            if (array_key_exists($language, $locales))
            {
                $locale = $language;
            } 
            else
            {
                $locale = Config::get('app.fallback_locale');      
            }  
        }

        $origin_locale = $origin . '-'  . $locale;

        // try combining origin and locale first
        if (array_key_exists($origin_locale, $locales))
        {
            if ($origin_locale != App::getLocale())
            {
                App::setLocale($origin_locale);
            }
        }
        else if (array_key_exists($locale, $locales) && $locale != App::getLocale())
        {
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}
