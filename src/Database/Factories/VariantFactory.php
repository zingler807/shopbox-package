<?php

namespace Laracle\ShopBox\Database\Factories;

use Laracle\ShopBox\Models\Variant;
use Laracle\ShopBox\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class VariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Variant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      $title = $this->faker->randomElement(['small', 'medium','large']);
      $slug = Str::slug($title);
        return [
            'product_id' => Product::factory(),
            'default_variant' => $this->faker->boolean,
            'title' => $title,
            'price' => $this->faker->randomDigit,
            'compare_price' => $this->faker->randomDigit,
            'sku' => $title,
            'track_qty' =>$this->faker->boolean,
            'qty' => $this->faker->randomDigit,
            'continue_selling' => $this->faker->boolean,
            'weight' =>200,
            'weight_unit' => $this->faker->randomElement(['kg', 'lbs']),
            'seo_title' => $title,
            'seo_handle' => $slug,
            'seo_description' => $this->faker->text
        ];
    }
}
