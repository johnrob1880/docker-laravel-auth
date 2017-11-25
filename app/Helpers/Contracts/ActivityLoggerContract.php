<?php

namespace App\Helpers\Contracts;

interface ActivityLoggerContract
{

    public function create($activity_id, $subject, $url, $ip, $method, $agent, $user_id);
    public function delete($id);
}