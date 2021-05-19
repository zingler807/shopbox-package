<?php

namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laracle\ShopBox\Models\Collection;
use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Facades\Cart;
use Log;
use Stripe\Stripe;


class CheckoutController extends Controller
{
  public function intent(Request $request)
  {

    $cart = Cart::get();

    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

    $intent = \Stripe\PaymentIntent::create([
      'amount' => $cart['total'],
      'currency' => $cart['currency']['code'],
      'metadata' => ['integration_check' => 'accept_a_payment'],
    ]);

    return $intent;

  }

}
