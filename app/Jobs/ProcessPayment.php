<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

use App\Constants\Activities;
use App\Helpers\Contracts\OmegaWebApiContract;
use App\Helpers\Contracts\ActivityLoggerContract;
use App\Facades\LocaleRouteFacade;

use App\User;
use App\Invoice;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $paymentType;
    protected $barcode;
    protected $total;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $paymentType, $barcode, $total)
    {
        $this->user = $user;
        $this->paymentType = $paymentType;
        $this->barcode = $barcode;
        $this->total = $total;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        $link = [
            'type' => $this->paymentType,
            'value' => $this->total,
            'description' => sprintf("$%d", number_format($this->total, 2))
        ];

        $paymentLink =  $api->linkBarcode($this->barcode, $link);

        if (is_null($paymentLink))
        {
            // error?? manually fail job??
            $activityLogger->create(
                Activities::PAYMENT_LINK_FAILURE, 
                "Attempt to link " . $this->paymentType . " payment failed for barcode: " . $this->barcode . '. Link failed.',
                LocaleRouteFacade::route('payment', ['barcode' => $this->barcode]),
                '127.0.0.1',
                'POST',
                null,
                $this->user->id);
           
            return;
        }

        $activityLogger->create(
            Activities::PAYMENT_LINK_SUCCESS, 
            $this->paymentType . " successfully linked for barcode: " . $this->barcode,
            LocaleRouteFacade::route('kit.payment', ['barcode' => $this->barcode]),
            '127.0.0.1',
            'POST',
            null,
            $this->user->id);
    }
}
