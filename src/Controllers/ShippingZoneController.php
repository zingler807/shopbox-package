<?php

namespace Laracle\ShopBox\Controllers;
use Laracle\ShopBox\Models\ShippingZone;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index(Request $request){
        return ShippingZone::paginate(20);
    }

    public function store(Request $request){

      $validatedData = $request->validate([
          'name' => 'required|max:255',
          'countries' => 'required'
      ],[
        'name.required' => 'Give your shipping zone a name',
        'name.max' => 'The name can be no larger than 255 characters',
        'countries.required' => 'Please choose one or more countries'
      ]);


      $zone = ShippingZone::updateOrCreate(
          ['id' => $request->id],
          $request->all()
      );
        return $zone;
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();
    }
}
