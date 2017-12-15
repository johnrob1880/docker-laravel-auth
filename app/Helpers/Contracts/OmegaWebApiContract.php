<?php

namespace App\Helpers\Contracts;

interface OmegaWebApiContract
{

    public function getPatientByEmail($email);
    public function getPatientByOmegaQuantId($omegaQuantId);
    public function createPatientFromUser($user, $barcode, $accountId);
    public function createPatientTest($barcode, $testId);
    public function getBarcodeInquiry($barcode);

    public function linkBarcode($barcode, $link);
    public function updatePatientResult($barcode, $result);
}