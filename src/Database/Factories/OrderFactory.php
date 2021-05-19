<?php

namespace Laracle\ShopBox\Database\Factories;

use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $paid = $this->faker->boolean();
        return [
            'customer_id' => Customer::factory(),
            'status' => ($paid) ? 'draft' : 'sent',
            'total' => $this->faker->randomNumber(2), // password
            'notes' => $this->faker->isbn10,
            'paid' => $paid,
            'fullfilled' => $this->faker->randomElement([1,0]),
            'billing_address_line1' => $this->faker->streetAddress,
            'billing_address_line2' => $this->faker->secondaryAddress,
            'billing_address_city' => $this->faker->citySuffix,
            'billing_address_country' => $this->faker->country,
            'billing_address_postcode' => $this->faker->postcode,
            'tracking_code' => $this->faker->numerify('##########'),
            'stripe_payment_id' => $this->faker->numerify('##########'),
            'stripe_card_last_4' => $this->faker->numerify('####'),
            'paid_at' => now()->subDays(2),
            'refunded_at' => $this->faker->randomElement([now()->subDays(1),null]),

        ];
    }
}
