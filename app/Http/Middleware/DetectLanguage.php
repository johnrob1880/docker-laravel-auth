<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Closure;
use Config;

class DetectLanguage
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
        $this->detect($request);

        return $next($request);
    }

    protected function detect($request)
    {
        
        $locales = Config::get('app.locales');

        $language = Config::get('app.locations.' . Config::get('app.location') . '.default_locale');

    
        if (Config::get('app.use_location_default_locale'))
        {
            
            $locale = Config::get('app.locations.' . Config::get('app.location') . '.default_locale');

            if ($locale && array_key_exists($locale, $locales))
            {
                $language = $locale;
            }
        }
        else 
        {
            // Browser language
            $browserLanguages = $this->httpAcceptLanguage();
            foreach ($browserLanguages as $lang => $weight)
            {
                if (array_key_exists($lang, $locales))
                {
                    $language = $lang;
                    break;
                }
            }
        }

        if (App::getLocale() != $language)
        {
            App::setLocale($language);
        }
    }

    function httpAcceptLanguage($httpAcceptLanguage = null)
    {
        if ($httpAcceptLanguage == null) {
            $httpAcceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
    
        $languages = explode(',', $httpAcceptLanguage);
        $result = array();
        foreach ($languages as $language) {
            $lang = explode(';q=', $language);
            // $lang == [language, weight], default weight = 1
            $result[$lang[0]] = isset($lang[1]) ? floatval($lang[1]) : 1;
        }
    
        arsort($result);
        return $result;
    }
}
