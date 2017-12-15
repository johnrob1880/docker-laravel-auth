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

class CreatePatientTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $testId;
    protected $barcode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $barcode, $testId)
    {
        $this->user = $user;
        $this->barcode = $barcode;
        $this->testId = $testId;
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
            $patientTest = $api->createPatientTest($this->barcode, $this->testId);
        } 
        catch (\Exception $e) {

            $activityLogger->create(
                Activities::PATIENT_TEST_CREATE_FAILURE, 
                "Attempt to create patient failed for user: " . $this->user->email . '. API error!',
                LocaleRouteFacade::route('kit.verify'),
                $this->user->ip,
                'GET',
                null,
                $this->user->id);

            return;
        }

        if (is_null($patientTest))
        {
            // error?? manually fail job??
            $activityLogger->create(
                Activities::PATIENT_TEST_CREATE_FAILURE, 
                "Attempt to create patient test failed for user: " . $this->user->email . '. User was null.',
                LocaleRouteFacade::route('kit.verify'),
                $this->user->ip,
                'GET',
                null,
                $this->user->id);

            return;
        }

        $activityLogger->create(
            Activities::PATIENT_TEST_CREATE_SUCCESS, 
            "Patient test created for user: " . $this->user->email,
            LocaleRouteFacade::route('kit.verify'),
            $this->user->ip,
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
