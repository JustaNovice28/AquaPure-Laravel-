<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'customer_name',
        'phone',
        'address',
        'gallons',
        'order_type',
        'price_per_gallon',
        'total_price',
        'status',
        'delivery_date',
        'delivery_time',
        'notes',

    ];

    protected $casts = [
        'delivery_date'    => 'date',
        'price_per_gallon' => 'decimal:2',
        'total_price'      => 'decimal:2',
        'gallons'          => 'integer',
        'deleted_at'        => 'datetime',
    ];
}