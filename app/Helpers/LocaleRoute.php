<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Url;

use App\Helpers\Contracts\LocaleRouteContract;

class LocaleRoute implements LocaleRouteContract
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = Config::get('app.fallback_origin') . '/' . Config::get('app.fallback_locale');
    }

    
    public function route($name, $action = null)
    {   
        $route = route($name, $action, false);

        $url = '/' . Config::get('app.origin') . '/' . App::getLocale() . $route;
        $url = rtrim($url, '?');

        return $url;
        
    }

    public function url($uri, $action = null)
    {
        $url = '/' . Config::get('app.origin') . '/' . App::getLocale() . $uri;
        
        return $url;
    }




}