<?php

namespace Laracle\ShopBox\Database\Seeders;
use Laracle\ShopBox\Models\Product;
use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Customer;
use Laracle\ShopBox\Models\Variant;
use Laracle\ShopBox\Models\Setting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopBoxSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //Product::factory(10)->create();
        User::factory()->create([
          'name' => 'Aaron Lumsden',
          'email' => 'aaron@laracle.com',
          'api_token' => Str::random(60)
        ]);
        Customer::factory(50)->create();
      //  Order::factory(50)->create();
        //$product = Product::factory(['has_variants'=>TRUE])->count(10)->hasVariants(3)->create();
        Setting::factory()->create();

        /*
        Variant::factory()->create([
          'product_id' => $product->id,
          'default_variant' => true
        ]);

        Variant::factory(3)->create([
          'product_id' => $product->id,
          'default_variant' => FALSE
        ]);
        */
    }
}


//php artisan db:seed --class=Laracle\\ShopBox\\Database\\Seeders\\ShopBoxSeeder
