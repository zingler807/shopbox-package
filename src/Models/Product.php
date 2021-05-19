<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracle\ShopBox\Traits\SearchTrait;
use Laracle\ShopBox\Traits\TagTrait;
use Laracle\ShopBox\Traits\ImageTrait;
use Laracle\ShopBox\Models\Collection;
use Laracle\ShopBox\Models\Variant;
use Laracle\ShopBox\Models\Vendor;
use Laracle\ShopBox\Models\ProductType;


class Product extends Model
{
    use HasFactory, SoftDeletes, SearchTrait, TagTrait, ImageTrait;

    protected $guarded = [];

    protected $search = ['title'];

    protected $with = ['tags','images','variants','defaultProduct'];

    protected $casts = [
      'has_variants' => 'boolean',
      'options' => 'array'
    ];

    protected static function newFactory(){
      return \Laracle\ShopBox\Database\Factories\ProductFactory::new();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class,'collection_product');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class)->where('default_variant',FALSE);
    }

    public function defaultProduct(){
        return $this->hasOne(Variant::class)->where('default_variant',TRUE);
    }

}
