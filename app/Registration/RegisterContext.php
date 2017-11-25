<?php
namespace App\Registration;

use App\Registration\States\RegisterStateNew;

class RegisterContext
{
    private $inquiry = null;
    private $registerState = null;

    public function __construct($inquiry)
    {
        $this->inquiry = $inquiry;
        $this->setRegisterState(new RegisterStateNew());
    }

    public function getInquiry()
    {
        return $this->inquiry;
    }

    public function getView()
    {
        return $this->registerState->getView($this);
    }

    public function setRegisterState($registerState)
    {
        $this->registerState = $registerState;
        return $this;
    }
}