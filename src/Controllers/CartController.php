<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Collection;
use Laracle\ShopBox\Facades\Cart;
use Log;


class CartController extends Controller
{
  public function index(Request $request)
  {
    return Cart::get();
  }

  public function store(Request $request){

        if ($request->page == 'discount') {

          $validated = $request->validate([
              'discount_code' => 'required'
          ],[
            'discount_code.required' => 'Please enter a code'
          ]);



          $discount = Cart::addDiscount($request->discount_code);

          if (isset($discount['error'])) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
               'discount_code' => ['Discount code is either invalid or has expired']
            ]);
            throw $error;
          }

        } else if ($request->page == 'info') {
          $validated = $request->validate([
              'email' => 'required',
              'first_name' => 'required',
              'last_name' => 'required',
              'address_line1' => 'required',
              'address_line2' => 'required',
              'address_city' => 'required',
              'address_country' => 'required',
              'address_postcode' => 'required',
              'phone' => 'required'
          ]);

          Cart::addInfo($request->all());
        } else if ($request->page == 'shipping'){

          $validated = $request->validate([
              'method' => 'required'
          ],[
            'method.required' => 'Please choose a shipping method'
          ]);

          Cart::addShipping($request->all());

        } else if($request->page == 'payment'){

          $validated = $request->validate([
              'intent' => 'required',
              'billing_first_name' => 'required_if:billing_address,other',
              'billing_last_name' => 'required_if:billing_address,other',
              'billing_address_line1' => 'required_if:billing_address,other',
              'billing_address_line2' => 'required_if:billing_address,other',
              'billing_city' => 'required_if:billing_address,other',
              'billing_country' => 'required_if:billing_address,other',
              'billing_post_code' => 'required_if:billing_address,other',
              'billing_phone' => 'required_if:billing_address,other'
          ],[
              'intent.required' => 'required',
              'billing_first_name.required_if' => 'Please enter the billing first name',
              'billing_last_name.required_if' => 'Please enter the billing last name',
              'billing_address_line1.required_if' => 'Please enter the 1st line of the billing address',
              'billing_address_line2.required_if' => 'Please enter the 2nd line of the billing address',
              'billing_city.required_if' => 'Please enter the city of the billing address',
              'billing_country.required_if' => 'Please choose a country for the billing address',
              'billing_post_code.required_if' => 'Please choose a post code for the billing address',
              'billing_phone.required_if' => 'Please enter the billing phone number'
          ]);

          $order = Cart::complete($request->all());

          return $order;
        }



        return Cart::get();
  }

}
