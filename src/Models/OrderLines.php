<?php
namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Variant;

class OrderLines extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['variant'];

    public function order(){
      return $this->belongsTo(Order::class);
    }

    public function variant(){
      return $this->hasOne(Variant::class,'id','variant_id');
    }
}
