<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class KitRegistration extends Model
{
    protected $table = "kit_registrations";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'barcode', 'user_id', 'current_step', 'test_name', 'test_price', 'is_complete'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
