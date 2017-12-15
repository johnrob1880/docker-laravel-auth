<?php

namespace App\Helpers\Contracts;

interface LocaleRouteContract
{
    public function route($name, $action = null);
    public function url($uri, $action = null);
}