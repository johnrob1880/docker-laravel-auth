<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

use App\Helpers\Contracts\OmegaWebApiContract;

class OmegaWebApi implements OmegaWebApiContract
{
    protected $client;

    protected $token;

    public function __construct()
    {
        $this->setToken();

        $origin = Config::get('app.origin');

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('webapi.origins.' . $origin . '.base_uri'),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json']
            ]);
    }

    public function getPatientByEmail($email)
    {
        $response = $this->client->request('GET', sprintf(Config::get('webapi.routes.get-patient-by-email'), $email));
        $body = $response->getBody();
        $patient = json_decode($body->getContents());
        
        return $patient;
    }

    public function getPatientByOmegaQuantId($omegaQuantId)
    {
        $response = $this->client->request('GET', sprintf(Config::get('webapi.routes.get-patient-by-oqid'), $omegaQuantId));
        $body = $response->getBody();
        $patient = json_decode($body->getContents());
        
        return $patient;
    }

    public function createPatientFromUser($user, $barcode, $accountId)
    {
        $origin = Config::get('app.origin');
        $country = Config::get('webapi.origins.' . $origin . '.country', Config::get('webapi.default_country'));

        $new_patient = [
            'AccountId' => $accountId,
            'FirstName' => $user->firstname,
            'LastName' => $user->lastname,
            'Email' => $user->email,
            'DateOfBirth' => $user->date_of_birth,
            "LanguageKey" => App::getLocale(),
            "Country" => $country,
            "OmegaQuantId" => $barcode
        ];

        $response = $this->client->request('POST', Config::get('webapi.routes.create-patient'), [
            'json' => $new_patient
        ]);
   
        $body = $response->getBody();
        $patient = json_decode($body->getContents());

        return $patient;        
    }

    public function createPatientTest($barcode, $testId)
    {
        $patient_test = [
            'OmegaQuantId' => $barcode,
            'TestId' => $testId,
            'PageIndex' => 0
        ];

        $response = $this->client->request('POST', Config::get('webapi.routes.create-patient-test'), [
            'json' => $patient_test
        ]);
   
        $body = $response->getBody();
        $patientTest = json_decode($body->getContents());

        return $patientTest; 
    }

    public function updatePatientResult($barcode, $result)
    {
        $patient_result = [
            'Barcode' => $barcode,
            'ResultDestination' => $result
        ];

        $response = $this->client->request('POST', Config::get('webapi.routes.update-patient-result'), [
            'json' => $patient_result
        ]);
   
        $body = $response->getBody();
        $patientResult = json_decode($body->getContents());

        return $patientResult;
    }

    public function getBarcodeInquiry($barcode)
    {
        $response = $this->client->request('GET', sprintf(Config::get('webapi.routes.barcode-inquiry'), $barcode));
        $body = $response->getBody();
        $inquiry = json_decode($body->getContents());
        return $inquiry;
    }


    public function linkBarcode($barcode, $link)
    {
        $barcodeLink = [
            'barcodeId' => $barcode,
            'linkType' => $link['type'],
            'linkValue' => $link['value'],
            'linkDescription' => $link['description'],
            'linkedBy' => 'web',
            'dateLinked' => date('Y-m-d')
        ];

        $response = $this->client->request('POST', Config::get('webapi.routes.link-barcode'), [
            'json' => $barcodeLink
        ]);

        $body = $response->getBody();
        $newLink = json_decode($body->getContents());

        return $newLink;  
    }

    private function setToken()
    {
        if (Session::has('token'))
        {
            $this->token = Session::get('token');
            return;
        }

        $origin = Config::get('app.origin');

        $tempClient = new \GuzzleHttp\Client([
            'base_uri' => Config::get('webapi.origins.' . $origin . '.base_uri'),
            'headers' => [
                'Content-Type' => 'application/json']
            ]);

        $response = $tempClient->request('POST', Config::get('webapi.routes.auth-token'), [
            'json' => [
                "Username" => Config::get('webapi.origins.'. $origin . '.username'),
                // encrypt?
                "Password" => Config::get('webapi.origins.'. $origin . '.password')
            ]
        ]);
   
        $this->token = $response->getBody();

        Session::put('token', $this->token);
    }
}