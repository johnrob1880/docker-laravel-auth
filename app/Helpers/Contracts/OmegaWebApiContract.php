<?php

namespace App\Helpers\Contracts;

interface OmegaWebApiContract
{

    public function getPatientByEmail($email);
    public function createPatientFromUser($user);
    public function getBarcodeInquiry($barcode);

    public function linkPayment($user, $invoice);
}