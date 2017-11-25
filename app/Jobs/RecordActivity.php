<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;

use App\Helpers\Contracts\ActivityLoggerContract;
use App\User;

class RecordActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $activity_id;
    protected $subject;
    protected $url;
    protected $method;
    protected $ip;
    protected $user_agent;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user, $activity_id, $subject)
    {
        $this->user = $user;
        $this->activity_id = $activity_id;
        $this->subject = $subject;
        $this->url = $request->fullUrl();
        $this->method = $request->method();
        $this->ip = $request->ip();
        $this->user_agent = $request->header('user-agent');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ActivityLoggerContract $activityLogger)
    {
        $activityLogger->create(
            $this->activity_id, 
            $this->subject,
            $this->url,
            $this->ip,
            $this->method,
            $this->user_agent,
            $this->user->id);
    }
}
