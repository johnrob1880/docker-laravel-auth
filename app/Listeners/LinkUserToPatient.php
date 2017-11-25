<?php

namespace App\Listeners;

use App\Events\UserWasRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

use Config;

class LinkUserToPatient
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        $user = $event->user;

        $client = new \GuzzleHttp\Client([
            'base_uri' => Config::get('app.omegaquant_api'),
            'headers' => [
                'Accept' => 'application/json',
                //'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json']
            ]);

        $response = $client->request('GET', 'api/v1/patients/email/' . $user->email);
        $body = $response->getBody();
        $patient = json_decode($body->getContents());

        if (is_null($patient))
        {
            $new_patient = [
                'FirstName' => $user->firstname,
                'LastName' => $user->lastname,
                'Email' => $user->email,
                'DateOfBirth' => $user->date_of_birth,
                "LanguageKey" => "en",
                "Country" => "usa"
            ];

            $options = [
                'json' => $new_patient
            ];

            $create_response = $client->request('POST', 'api/v1/patients', [
                'headers' => [
                    'content-type' => 'application/json'
                ],
                'body' => json_encode($new_patient)
            ]);
       
            $body = $create_response->getBody();
            $patient = json_decode($body->getContents());

            if (!is_null($patient))
            {
                $user->omegaquant_id = $patient->omegaQuantId;
                $user->save();
            }
        } 
        else 
        {
            // update the omegaquant id
            $user->omegaquant_id = $patient->omegaQuantId;
            $user->save();
        }
        
    }
}
