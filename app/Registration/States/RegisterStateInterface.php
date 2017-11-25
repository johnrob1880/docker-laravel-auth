<?php

namespace App\Registration\States;

interface RegisterStateInterface 
{
    public function getView($registerContext);
}