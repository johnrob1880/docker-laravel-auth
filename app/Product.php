<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $attributes = ['price' => 0, 'selected' => false, 'features' => []];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'name', 'description', 'product_group', 'product_index'
    ];

    public static function hasFeature($model, $feature_id) {
 
        $first = array_first($model['features'], function ($value, $key) use ($feature_id) {
            return $value->feature_id == $feature_id;
        });

        return !is_null($first);
    }
}
