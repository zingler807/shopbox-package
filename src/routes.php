<?php

use Illuminate\Support\Facades\Route;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Models\Tag;
use Laracle\ShopBox\Controllers\AuthController;
use Laracle\ShopBox\Controllers\SettingController;
use Laracle\ShopBox\Controllers\ProductController;
use Laracle\ShopBox\Controllers\DiscountController;
use Laracle\ShopBox\Controllers\CustomerController;
use Laracle\ShopBox\Controllers\CollectionController;
use Laracle\ShopBox\Controllers\OrderController;
use Laracle\ShopBox\Controllers\ImageController;
use Laracle\ShopBox\Controllers\CheckoutController;
use Laracle\ShopBox\Controllers\CartController;
use Laracle\ShopBox\Controllers\ShippingRateController;
use Laracle\ShopBox\Controllers\ShippingZoneController;
use Laracle\ShopBox\Controllers\ReportController;
use Illuminate\Routing\Middleware\SubstituteBindings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Product
// Variants
// Collections
// collections_product
// SEO
// Currencies
// order
// customers
// addresses
// images
// pages
// vouchers
// Settings
// Abandoned Checkout

use Laracle\ShopBox\Models\Product;
use Laracle\ShopBox\Models\Order;

Route::bind('product', function ($id) {
    return Product::withTrashed()->where('id', $id)->first();
});

Route::bind('order', function ($id) {
    return Order::withTrashed()->where('id', $id)->first();
});

Route::bind('customer', function ($id) {
    return Customer::withTrashed()->where('id', $id)->first();
});



Route::group(['prefix' => '/api','middleware'=>['api']], function () {

  Route::get('/collections/search', [CollectionController::class,'search']);

  Route::post('/customers/{customer}/comments', [CustomerController::class,'addComment']);
  Route::get('/customers/{customer}/comments', [CustomerController::class,'getComments']);

  Route::post('/orders/{order}/comments', [OrderController::class,'addComment']);
  Route::get('/orders/{order}/comments', [OrderController::class,'getComments']);

  Route::get('product/vendors', [ProductController::class,'vendors']);
  Route::get('product/types', [ProductController::class,'types']);

  Route::apiResources([
      'products' => ProductController::class,
      'settings' => SettingController::class,
      'discounts' => DiscountController::class,
      'customers' => CustomerController::class,
      'collections' => CollectionController::class,
      'orders' => OrderController::class,
      'cart' => CartController::class,
      'shipping-zone' => ShippingZoneController::class,
      'shipping-rate' => ShippingRateController::class,
      'reports' => ReportController::class
  ]);

  Route::get('shipping-rate/country/{country}', [ShippingRateController::class,'byCountry']);



  Route::post('images/order', [ImageController::class,'changeOrder']);
  Route::delete('images/{image}', [ImageController::class,'destroy']);
  Route::put('images/{image}', [ImageController::class,'update']);
  Route::post('update-password', [AuthController::class,'updatePassword']);

  Route::get('/checkout/intent',[CheckoutController::class,'intent']);

});

Route::group(['prefix' => '/api'], function () {

  Route::post('login', [AuthController::class,'login']);
  Route::post('register', [AuthController::class,'register']);
  Route::post('forgot', [AuthController::class,'forgot']);
  Route::post('reset', [AuthController::class,'resetPassword']);

});


Route::get('image/{image}',[ImageController::class,'show']);

Route::get('/api/tracking',[OrderController::class,'orderLookup']);

Route::get('/admin/{any?}', function () {
    return view('laracle::admin');
})->where('any', '.*');

Route::get('/checkout', function () {
    return view('laracle::admin');
});

Route::get('/tracking', function () {
    return view('laracle::admin');
});
