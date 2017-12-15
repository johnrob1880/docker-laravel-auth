<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

use App\Helpers\Contracts\OmegaWebApiContract;
use App\Helpers\Contracts\ActivityLoggerContract;
use App\Constants\Activities;
use App\Facades\LocaleRouteFacade;

use App\User;

class CreatePatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $accountId;
    protected $barcode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $barcode, $accountId)
    {
        $this->user = $user;
        $this->barcode = $barcode;
        $this->accountId = $accountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        // If the patient already exists, the api will just return that patient...
        try
        {
            $patient = $api->createPatientFromUser($this->user, $this->barcode, $this->accountId);
        } 
        catch (\Exception $e) {

            $activityLogger->create(
                Activities::PATIENT_CREATE_FAILURE, 
                "Attempt to create patient failed for user: " . $this->user->email . '. API error!',
                LocaleRouteFacade::route('register'),
                '127.0.0.1',
                'GET',
                null,
                $this->user->id);

            return;
        }

        if (is_null($patient))
        {
            // error?? manually fail job??
            $activityLogger->create(
                Activities::PATIENT_CREATE_FAILURE, 
                "Attempt to create patient failed for user: " . $this->user->email . '. User was null.',
                LocaleRouteFacade::route('register'),
                '127.0.0.1',
                'GET',
                null,
                $this->user->id);

            return;
        }

        $activityLogger->create(
            Activities::PATIENT_CREATE_SUCCESS, 
            "Patient created for user: " . $this->user->email,
            LocaleRouteFacade::route('register'),
            '127.0.0.1',
            'GET',
            null,
            $this->user->id);   
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }
}
