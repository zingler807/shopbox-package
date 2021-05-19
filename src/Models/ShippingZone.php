<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Models\ShippingRate;

class ShippingZone extends Model
{
    use HasFactory;

    protected $casts = [
      'countries' => 'array'
    ];

    protected $with = ['rates'];

    protected $guarded = [];

    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}
