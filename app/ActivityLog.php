<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{

    protected $table = "activities";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_id', 'subject', 'url', 'method', 'ip', 'agent', 'user_id'
    ];
}
