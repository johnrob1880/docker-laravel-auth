<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['title', 'test_price', 'analysis_cost', 'total', 'paypal_invoice_id', 'payment_status', 'error_msg', 'user_id', 'kit_registration_id'];

    public function getPaidAttribute() {
    	if ($this->payment_status != 'Completed') {
    		return false;
    	}
    	return true;
    }
}
