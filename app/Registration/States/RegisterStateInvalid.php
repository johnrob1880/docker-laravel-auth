<?php
namespace App\Registration\States;

class RegisterStateInvalid implements RegisterStateInterface
{
    public function getView($registerContext)
    {
        return view('kits.invalid');
    }
}