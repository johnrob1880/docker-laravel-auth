<?php

namespace App\Registration\States;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\KitRegistration;


class RegisterStateNew implements RegisterStateInterface
{
    
    public function getView($registerContext)
    {
        $inquiry = $registerContext->getInquiry();

        if (is_null($inquiry) || is_null($inquiry->barcodeValue))
        {
            return $registerContext->setRegisterState(new RegisterStateInvalid())->getView();
        }

        $user = Auth::user();

        $kit = KitRegistration::where('barcode', $inquiry->barcodeValue)->first();
        
        if (!is_null($kit)) 
        {
            return $registerContext.setRegisterState(new RegisterStateUpgrade($kit))->getView();
        }

        return view('kits.new');
    }
}