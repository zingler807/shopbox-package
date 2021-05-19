<?php

namespace Laracle\ShopBox\Database\Factories;

use Laracle\ShopBox\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;


class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'currency' => 'GBP',
            'timezone' => 'Europe/London',
        ];
    }
}
