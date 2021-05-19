<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Traits\SearchTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracle\ShopBox\Traits\TagTrait;
use Laracle\ShopBox\Traits\CommentTrait;
use Laracle\ShopBox\Models\Order;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Customer extends Model
{
    use HasFactory, SearchTrait, TagTrait, CommentTrait, SoftDeletes;

    protected $guarded = [];

    protected $search = ['first_name','last_name','phone','email'];

    protected $with = ['tags'];

    protected static function newFactory(){
      return \Laracle\ShopBox\Database\Factories\CustomerFactory::new();
    }

    public function orders(){
      return $this->hasMany(Order::class);
    }




}
