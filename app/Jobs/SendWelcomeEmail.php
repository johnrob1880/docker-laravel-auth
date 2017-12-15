<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

use Mail;
use App\User;
use App\Mail\WelcomeEmail;
use App\Helpers\Contracts\ActivityLoggerContract;
use App\Constants\Activities;
use App\Facades\LocaleRouteFacade;

class SendWelcomeEmail implements ShouldQueue
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
    public function handle(ActivityLoggerContract $activityLogger)
    {
        $email = new WelcomeEmail($this->user);
        Mail::to($this->user->email)->send($email);

        $activityLogger->create(
            Activities::WELCOME_EMAIL_SENT, 
            "Welcome email sent to " . $this->user->email . '.',
            LocaleRouteFacade::route('register'),
            '127.0.0.1',
            'GET',
            null,
            $this->user->id);
    }
}
