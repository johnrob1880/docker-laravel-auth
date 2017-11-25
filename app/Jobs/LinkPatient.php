<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Psr7\Request as GuzzleRequest;

use App\Helpers\Contracts\OmegaWebApiContract;
use App\Helpers\Contracts\ActivityLoggerContract;
use App\Constants\Activities;
use App\User;

class LinkPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        // If the patient already exists, the api will just return that patient...
        $patient = $api->createPatientFromUser($this->user);

        if (is_null($patient))
        {
            // error?? manually fail job??
            $activityLogger->create(Activities::PATIENT_LINK_FAILURE, "Attemp to link patient failed for user: " . $this->user->email);

            return;
        }

        // Link the omegaquant id
        $this->user->omegaquant_id = $patient->omegaQuantId;
        $this->user->save();

        $activityLogger->create(Activities::PATIENT_LINK_SUCCESS, "Patient successfully linked for user: " . $this->user->email);

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
