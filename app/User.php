<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'date_of_birth', 'results_via_email', 'verified', 
        'email', 'password', 'origin', 'locale', 'date_of_last_login', 'last_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getIpAttribute() {
    	if (!is_null($this->last_ip)) {
    		return $this->last_ip;
    	}
    	return '127.0.0.1';
    }

    public function getFullNameAttribute() {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }
}
