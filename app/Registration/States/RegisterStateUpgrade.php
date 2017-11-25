<?php
namespace App\Registration\States;

class RegisterStateUpgrade implements RegisterStateInterface
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

        if (!$inquiry->canUpgrade)
        {
            return $registerContext->setRegisterState(new RegisterStatePayment($kit))->getView();
        }

        return view('kits.upgrade', [
            'kit' => $this->kit,
            'test' => $inquiry->test,
            'upgrades' => $inquiry->testUpgrades
        ]);
    }
}