<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $fillable = [
        'shipping_zone_id',
        'country',
        'city',
        'postal_code',
    ];
}
