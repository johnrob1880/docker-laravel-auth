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

class LinkUpgradeCost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $barcode;
    protected $upgrade;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $barcode, $upgrade)
    {
        $this->user = $user;
        $this->barcode = $barcode;
        $this->upgrade = $upgrade;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OmegaWebApiContract $api, ActivityLoggerContract $activityLogger)
    {
        $link = [
            'type' => 'TestUpgradeCost',
            'value' => $this->upgrade->upgradePrice,
            'description' => sprintf("$%d", number_format($this->upgrade->upgradePrice, 2)) 
        ];

        $testUpgradeCost =  $api->linkBarcode($this->barcode, $link);


        if (is_null($testUpgradeCost))
        {
            // error?? manually fail job??
            
            $activityLogger->create(
                Activities::TEST_UPGRADE_COST_LINK_FAILURE, 
                "Attempt to link test upgrade cost failed for barcode: " . $this->barcode . '. Link failed.',
                LocaleRouteFacade::route('upgrade', ['barcode' => $this->barcode]),
                '127.0.0.1',
                'POST',
                null,
                $this->user->id);

            return;
        }

        $activityLogger->create(
            Activities::TEST_UPGRADE_COST_LINK_SUCCESS, 
            "Test upgrade cost [" . sprintf("$%d", number_format($this->upgrade->upgradePrice, 2)) . "] for " . $this->upgrade->test . " successfully linked for barcode: " . $this->barcode,
            LocaleRouteFacade::route('kit.upgrade', ['barcode' => $this->barcode]),
            '127.0.0.1',
            'POST',
            null,
            $this->user->id);

    }
}
