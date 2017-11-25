<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

class LocationDetectorServiceProvider extends ServiceProvider
{
    /**
     * Symfony translator.
     * @var Translator
     */
    protected $translator;

    /**
     * Illuminate Request.
     * @var Request
     */
    protected $request;
    
    /**
     * Configurations repository.
     * @var Config
     */
    protected $config;


    /**
     * Detected language.
     * @var string
     */
    protected $language;

    /**
     * Detected location.
     * @var string
     */
    protected $location;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->translator = $this->app['translator'];
        $this->request = $this->app['request'];
        $this->config = $this->app['config'];

        

        $this->registerLocationDetect();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Regiter in container the routePrefix.
     *
     * @return void
     */
    protected function registerLocationDetect()
    {
        $this->app->bind(
            'location.detect',
            function () {
                return $this->detect();
            }
        );
    }

    protected function detect()
    {
        $this->detectLocation();
        $this->detectLanguage();
        

        return $this->language;
    }

    protected function detectLanguage()
    {
        // default browser language
        $language = substr($this->request->server->get('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        $locales = $this->config->get('app.locales');
        $locale = $this->config->get('app.locale');
    
        if (!array_key_exists($language, $locales))
        {
            $language = $this->config->get('app.locations.' . $locale . '.default_locale');
        }

        if ($locale != $language)
        {
            $this->config->set('app.locale', $language);        
        }
        
        if ($this->translator->getLocale() != $language)
        {
            $this->translator->setLocale($language);
        }

        $this->language = $language;
    }

    protected function detectLocation()
    {
        $hostname = parse_url(strtolower($this->request->url()), PHP_URL_HOST);
        $url_array = explode('.', $hostname);
        $location = $url_array[0];
        $locations = $this->config->get('app.locations');

        $this->location = $this->config->get('app.fallback_location');
   
        if (array_key_exists($location, $locations)) {
            if ($this->config->get('app.location') != $location)
            {
                $this->config->set('app.location', $location);
                $this->location = $location;
                Event::dispatch(new \App\Events\LocationUpdated($location));           
            }
        }
    }

}
