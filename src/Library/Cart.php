<?php

namespace Laracle\ShopBox\Library;
use Laracle\ShopBox\Models\Variant;
use Laracle\ShopBox\Models\Setting;
use Laracle\ShopBox\Models\Discount;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\OrderLines;
use Laracle\ShopBox\Library\CurrencySymbols;
use Request;
use Log;


class Cart
{


    public function add(int $variant_id,int $qty = 1){

      $variant = Variant::with('product')->find($variant_id);
      if (!$variant || !$this->inStock($variant)) { return false; }


      $cart = $this->get();
      $found = false;

      // If the item is already in the cart then update its qty;
      foreach ($cart['items'] as $index => $var) {
          if ($var['id'] == $variant['id']) {

            $cart['items'][$index]['cart_qty'] += $qty;
            $found = true;

          }
      }

      if (!$found) {
        $variant['cart_qty'] = $qty;
        array_push($cart['items'],$variant);
      }

      Request::session()->put('cart',$cart);

    }

    public function remove(int $variant_id){

        $cart = $this->get();

        foreach ($cart['items'] as $index => $prod) {
            if ($prod['id'] == $variant_id) {
              unset($cart['items'][$index]);
            }
        }


        Request::session()->put('cart',$cart);
    }

    public function get(){

        if ($cart = Request::session()->get('cart')) {
          $cart['product_count'] = $this->getProductCount($cart);
          $cart['item_count'] = $this->getItemCount($cart);
          $cart['subtotal'] = $this->getSubTotal($cart);
          $cart['discount_amount'] = $this->getDiscountTotal($cart);
          $cart['total'] = $this->getTotal($cart);
          $cart['currency'] = $this->getCurrency();
          return $cart;
        } else {
          $arr = [
            'items'=> [],
            'item_count' => 0,
            'product_count' => 0,
            'discount' => null,
            'discount_amount' => null,
            'total' => 0,
            'shipping' => 0,
            'currency' => 0,
            'subtotal' => 0,
            'note' => '',
            'information' => null,
            'shipping_information' => null,
            'billing_address' => ''
          ];
          $cart = Request::session()->put('cart',$arr);
          return $arr;
        }

    }

    private function getProductCount($cart){
        return count($cart['items']);
    }

    public function addInfo($info){
        $cart = $this->get();
        $cart['information'] = $info;
        Request::session()->put('cart',$cart);
    }

    public function addShipping($shipping){
        $cart = $this->get();
        $cart['shipping'] = $shipping['method']['price'];
        $cart['shipping_information'] = $shipping['method'];
        Request::session()->put('cart',$cart);
    }

    private function getItemCount($cart){
      $count = 0;
      foreach ($cart['items'] as $prod) {
            $count+=$prod['cart_qty'];
      }
      return $count;
    }

    private function getSubTotal($cart){
      $total = 0;
      foreach ($cart['items'] as $prod) {
          $total+=($this->convertToMoneyFormat($prod['price']) * $prod['cart_qty']);
      }


      return $total;
    }

    private function getDiscountTotal($cart){


      if (isset($cart['discount'])) {
        if ($cart['discount']['type'] == 'percentage') {
            $discount = $cart['discount']['percentage'] / 100 * $cart['subtotal'];
        } else if ($cart['discount']['type'] == 'fixed'){
            $discount = $this->convertToMoneyFormat($cart['discount']['value']);
        } else if ($cart['discount']['type'] == 'free_shipping'){
            $discount = $this->convertToMoneyFormat($cart['shipping']);
        }

        return $discount;
      }



    }

    private function getTotal($cart){
      $total = $this->getSubTotal($cart);

      $total+=$this->convertToMoneyFormat($cart['shipping']);

      if (isset($cart['discount_amount'])) {
        $total = $total - $cart['discount_amount'];
      }
      return $total;
    }


    public function clear(){
        Request::session()->forget('cart');
    }

    public function updateQty(int $product,int $qty = 1){

      $cart = $this->get();

      // If the item is already in the cart then update its qty;
      foreach ($cart['items'] as $index => $prod) {
          if ($prod['id'] == $product) {

            $cart['items'][$index]['cart_qty'] = $qty;
          }
      }

    }

    public function inStock($product){

        if (!$product['track_qty']) {
          return true;
        }
        if ((Bool)$product['track_qty'] && (int) $product['qty'] > 0) {
          return true;
        }
        return false;
    }

    public function addDiscount($code){

        $discount = Discount::where('code',$code)->first();

        if (!$discount) { return ['error'=>'Invalid discount code']; }

        if (!$discount->isValid()) { return ['error'=>'Discount code has expired']; }

        $cart = $this->get();
        $cart['discount'] = $discount;


        Request::session()->put('cart',$cart);
        return true;

    }

    private function convertToMoneyFormat($amount){
      return (float)str_replace(".","",number_format($amount,2));
    }

    public function removeDiscount(){
      $cart = $this->get();
      $cart['discount'] = null;
      $cart['discount_amount'] = null;
      Request::session()->put('cart',$cart);
    }

    private function getCurrency(){
      $settings = Setting::select('currency')->first();
      $arr = [];
      if ($settings) {
          $currency = new CurrencySymbols;
          $symbol = $currency->getCurrency($settings['currency']);
        $arr = [
          'code' => $settings['currency'],
          'symbol' => $symbol,
          'symbol_decoded' => html_entity_decode($symbol)
        ];
      }

      return $arr;
    }

    public function complete($request){


      $cart = $this->get();
      //return $cart;
      if (empty($cart['items'])) { return false; }

      // Check if custom exists or update existing customer by email.
      $customerInfo = collect($cart['information'])->forget('page')->toArray();
      $customerInfo['address_country'] = $cart['information']['address_country']['alpha-3'];
      $customer = Customer::updateOrCreate(
          ['email' => $customerInfo['email']],
          $customerInfo
      );

      // If success create order & orderlines

      $order = Order::create([
        'customer_id' => $customer->id,
        'shipping_rate_id' => $cart['shipping_information']['id'],
        'status' => 'paid',
        'subtotal' => number_format(($cart['subtotal'] /100), 2, '.', ' '),
        'shipping' => $cart['shipping'],
        'discount_amount' => $cart['discount_amount'],
        'total' => number_format(($cart['total'] /100), 2, '.', ' '),
        'billing_first_name' => ($request['billing_address'] == 'same') ? $customer->first_name : $request['billing_first_name'],
        'billing_last_name' => ($request['billing_address'] == 'same') ? $customer->first_name : $request['billing_last_name'],
        'billing_address_line1' => ($request['billing_address'] == 'same') ? $customer->address_line1 : $request['billing_address_line1'],
        'billing_address_line2' => ($request['billing_address'] == 'same') ? $customer->address_line2 : $request['billing_address_line2'],
        'billing_address_city' => ($request['billing_address'] == 'same') ? $customer->address_city : $request['billing_city'],
        'billing_address_country' => ($request['billing_address'] == 'same') ? $customer->address_country : $request['billing_country']['alpha-3'],
        'billing_address_postcode' => ($request['billing_address'] == 'same') ? $customer->address_postcode : $request['billing_post_code'],
        'billing_phone' => ($request['billing_address'] == 'same') ? $customer->phone : $request['billing_phone'],
        'stripe_payment_id' => $request['intent']['id'],
        'paid_at' => now(),
        'discount_code' => (isset($cart['discount'])) ? $cart['discount']['code'] : null ,
      ]);

      $order->ref = '#'.str_pad($order->id, 8, "0", STR_PAD_LEFT);
      $order->save();



      $lines = collect($cart['items'])->map(function($item) use ($order){

          return new OrderLines([
            'order_id' => $order->id,
            'variant_id' => $item->id,
            'qty' => $item->cart_qty,
            'price' => $item->price * $item->cart_qty,
            'option' => (!$item->default_variant) ? $item->option : null
          ]);
      });

      $order->lines()->saveMany($lines);

      return $order;


    }
}
