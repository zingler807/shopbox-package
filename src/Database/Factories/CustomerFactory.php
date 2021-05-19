<?php

namespace Laracle\ShopBox\Database\Factories;

use Laracle\ShopBox\Models\Order;
use Laracle\ShopBox\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email, // password
            'phone' => $this->faker->isbn10,
            'address_line1' => $this->faker->streetAddress,
            'address_line2' => $this->faker->secondaryAddress,
            'address_city' => $this->faker->citySuffix,
            'address_country' => $this->faker->country,
            'address_postcode' => $this->faker->postcode,
            'note' => $this->faker->sentence,
            //'syncTags' => ['tag1','tag2']
        ];
    }
}
