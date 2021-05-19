<?php

namespace Laracle\ShopBox\Controllers;
use Laracle\ShopBox\Models\ShippingRate;
use Laracle\ShopBox\Models\ShippingZone;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
  public function store(Request $request){

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'condition_type' => 'required',
        ],[
          'name.required' => 'Give your shipping rate a name to display to customers',
          'name.max' => 'The name can be no larger than 255 characters'
        ]);


        $rate = ShippingRate::updateOrCreate(
            ['id' => $request->id],
            $request->all()
        );
        return $rate;
  }

  public function destroy(ShippingRate $shippingRate)
  {
      $shippingRate->delete();
  }

  public function byCountry($country){
      // Get the current shipping rates for the specified country
      $name = 'alpha3';
      $zones = ShippingZone::whereJsonContains('countries',['alpha-3'=>$country])->firstOrFail();
      return $zones['rates'];
  }
}
