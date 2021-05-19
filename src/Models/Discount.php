<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Traits\SearchTrait;
use Carbon\Carbon;
use Log;

class Discount extends Model
{
    use HasFactory, SearchTrait;

    protected $guarded = [];

    protected $casts = [
      'add_expiry' => 'boolean'
    ];

    protected $search = ['code'];

    public function getStartDateAttribute($value){
      return Carbon::parse($value)->toIso8601String();;
    }

    public function getEndDateAttribute($value){
      return Carbon::parse($value)->toIso8601String();;
    }

    public function isValid(){

      if ($this->add_expiry) {
        $check = Carbon::now()->between(Carbon::parse($this->start_date),Carbon::parse($this->end_date));
      } else {
        $check = Carbon::now()->gt(Carbon::parse($this->start_date));
      }

      return $check;

    }
}
