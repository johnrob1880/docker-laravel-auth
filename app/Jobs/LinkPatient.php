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
use App\Facades\LocaleRouteFacade;

use App\Constants\Activities;
use App\User;

class LinkPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $barcode;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($barcode, User $user)
    {
        $this->user = $user;
        $this->barcode = $barcode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        $patient = $api->getPatientByOmegaQuantId($this->barcode);

        if (is_null($patient))
        {
            $activityLogger->create(
                Activities::PATIENT_LINK_FAILURE, 
                "Attempt to link patient failed for id: " . $this->barcode . " ,user: " . $this->user->email . ". Patient not found.",
                LocaleRouteFacade::route('register'),
                '127.0.0.1',
                'POST',
                null,
                $this->user->id);
           
            $this->failed();
            return;
        }

        // $"{model.OmegaQuantId} ({model.FirstName} {model.LastName})"
        $link = $api->linkBarcode(
            $this->barcode, 
            [
                'type' => 'Patient',
                'value' => $patient->id,
                'description' => sprintf("%s (%s %s)", $patient->omegaQuantId, $patient->firstName, $patient->lastName)
            ]);

        if (is_null($link))
        {
            // error?? manually fail job??
            $activityLogger->create(
                Activities::PATIENT_LINK_FAILURE, 
                "Attempt to link patient failed for id: " . $this->barcode . " ,user: " . $this->user->email . '. Link failed.',
                LocaleRouteFacade::route('register'),
                '127.0.0.1',
                'POST',
                null,
                $this->user->id);

            return;
        }

        $activityLogger->create(
            Activities::PATIENT_LINK_SUCCESS, 
            "Patient successfully linked to id: " . $this->barcode . " for user: " . $this->user->email,
            LocaleRouteFacade::route('register'),
            '127.0.0.1',
            'POST',
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
