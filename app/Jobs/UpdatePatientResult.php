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

class UpdatePatientResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $barcode;
    protected $result;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $barcode, $result)
    {
        $this->user = $user;
        $this->barcode = $barcode;
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        $patientResult = null;

        try
        {
            $patientResult = $api->updatePatientResult($this->barcode, $this->result);
        } 
        catch (\Exception $e) 
        {

            $activityLogger->create(
                Activities::UPDATE_PATIENT_RESULT_FAILURE, 
                "Attempt to update patient result failed for user: " . $this->user->email . '. API error!',
                LocaleRouteFacade::route('kit.verify'),
                $this->user->ip,
                'GET',
                null,
                $this->user->id);

            throw $e;
        }

        if (is_null($patientResult))
        {
            // error?? manually fail job??
            $activityLogger->create(
                Activities::UPDATE_PATIENT_RESULT_FAILURE, 
                "Attempt to update patient result failed for user: " . $this->user->email . '. Result was null.',
                LocaleRouteFacade::route('kit.verify'),
                $this->user->ip,
                'GET',
                null,
                $this->user->id);

            throw new \Exception("Patient result was null for user: " . $this->user->id);
        }

        $activityLogger->create(
            Activities::UPDATE_PATIENT_RESULT_SUCCESS, 
            "Patient result updated for user: " . $this->user->email,
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
