<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Contracts\ActivityLoggerContract;
use App\ActivityLog;

class ActivityLogger implements ActivityLoggerContract
{
    public function __construct()
    {
       
    }

    public function create($activity_id, $subject, $url, $ip, $method, $agent, $user_id)
    {
        $log = [];
        $log['activity_id'] = $activity_id;
    	$log['subject'] = $subject;
    	$log['url'] = $url; // Request::fullUrl();
    	$log['method'] = $method; // Request::method();
    	$log['ip'] = $ip; // Request::ip();
        $log['agent'] = $agent; // Request::header('user-agent');
        $log['user_id'] = $user_id;

        
    	ActivityLog::create($log);
    }

    public function delete($id)
    {
        ActivityLog::destroy($id);
    }
}