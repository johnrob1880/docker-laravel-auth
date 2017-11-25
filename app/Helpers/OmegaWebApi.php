<?php

namespace App\Helpers;

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

        $location = Config::get('app.location');

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('webapi.locations.' . $location . '.base_uri'),
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

    public function createPatientFromUser($user)
    {
        $location = Config::get('app.location');

        $new_patient = [
            'FirstName' => $user->firstname,
            'LastName' => $user->lastname,
            'Email' => $user->email,
            'DateOfBirth' => $user->date_of_birth,
            "LanguageKey" => Config::get('webapi.locations.' . $location . '.default_locale'),
            "Country" => Config::get('webapi.locations.' . $location . '.country')
        ];

        $response = $this->client->request('POST', Config::get('webapi.routes.create-patient'), [
            'json' => $new_patient
        ]);
   
        $body = $response->getBody();
        $patient = json_decode($body->getContents());

        return $patient;        
    }

    public function getBarcodeInquiry($barcode)
    {
        $response = $this->client->request('GET', sprintf(Config::get('webapi.routes.barcode-inquiry'), $barcode));
        $body = $response->getBody();
        $inquiry = json_decode($body->getContents());
        return $inquiry;
    }


    public function linkPayment($user, $invoice)
    {

    }

    private function setToken()
    {
        if (Session::has('token'))
        {
            $this->token = Session::get('token');
            return;
        }

        $location = Config::get('app.location');

        $tempClient = new \GuzzleHttp\Client([
            'base_uri' => Config::get('webapi.locations.' . $location . '.base_uri'),
            'headers' => [
                'Content-Type' => 'application/json']
            ]);

        $response = $tempClient->request('POST', Config::get('webapi.routes.auth-token'), [
            'json' => [
                "Username" => Config::get('webapi.locations.'. $location . '.username'),
                // encrypt?
                "Password" => Config::get('webapi.locations.'. $location . '.password')
            ]
        ]);
   
        $this->token = $response->getBody();

        Session::put('token', $this->token);
    }
}