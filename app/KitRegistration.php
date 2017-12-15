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
        'barcode', 'user_id', 'test_name', 'test_id', 'upgraded_from_test_id', 'test_price', 'analysis_cost', 'is_complete', 'origin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
