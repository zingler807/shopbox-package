<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Models\Product;

class Variant extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
      'default_variant' => 'boolean',
      'track_qty' => 'boolean',
      'continue_selling' => 'boolean'
    ];

    protected static function newFactory(){
      return \Laracle\ShopBox\Database\Factories\VariantFactory::new();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
