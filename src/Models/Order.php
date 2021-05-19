<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Traits\SearchTrait;
use Laracle\ShopBox\Traits\TagTrait;
use Laracle\ShopBox\Traits\CommentTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Models\OrderLines;

class Order extends Model
{
    use HasFactory, SearchTrait, TagTrait, CommentTrait, SoftDeletes;

    protected $guarded = [];

    protected $search = ['id'];

    protected $with = ['customer','tags'];

    protected static function newFactory(){
      return \Laracle\ShopBox\Database\Factories\OrderFactory::new();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines(){
      return $this->hasMany(OrderLines::class);
    }

}
