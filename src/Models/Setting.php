<?php

namespace Laracle\ShopBox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laracle\ShopBox\Library\CurrencySymbols;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = array('currency_format');

    protected static function newFactory(){
      return \Laracle\ShopBox\Database\Factories\SettingFactory::new();
    }


    public function getCurrencyFormatAttribute()
    {
        $currency = new CurrencySymbols;

        $symbol = $currency->getCurrency($this->currency);

        return [
          'code' => $this->currency,
          'symbol' => $symbol,
          'symbol_decoded' => html_entity_decode($symbol)
        ];

    }
}
