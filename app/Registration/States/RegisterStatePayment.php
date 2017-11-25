<?php
namespace App\Registration\States;

class RegisterStatePayment implements RegisterStateInterface
{
    private $kit;

    public function __construct($kit)
    {
        $this->kit = $kit;
    }

    public function getView($registerContext)
    {
        $inquiry = $registerContext->getInquiry();

        if (is_null($inquiry) || is_null($inquiry->barcodeValue))
        {   
            return $registerContext->setRegisterState(new RegisterStateInvalid())->getView();
        }

        if ($inquiry->isTestPricePaid && $inquiry->isAnalysisCostPaid)
        {
            return $registerContext->setRegisterState(new RegisterStateConfirm($kit))->getView();
        }

        return view('kits.payment', [
            'kit' => $this->kit
        ]);
    }
}