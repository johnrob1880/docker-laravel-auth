<?php  

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LocaleRouteFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'locale_route';
    }
}