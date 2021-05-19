<?php

namespace Laracle\ShopBox\Database\Factories;

use Laracle\ShopBox\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
      $title = $this->faker->sentence;
      $slug = Str::slug($title);
      $hasVariants = $this->faker->boolean;
      $options = [[
        'name' => 'Size',
        'options' => ($hasVariants) ? ['small','medium','large'] : NULL
        ]];

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->text,
            'status' => $this->faker->randomElement(['active', 'draft']),
            'has_variants' => $hasVariants,
            'options' => $options,
            'seo_title' => $title,
            'seo_handle' =>$slug,
            'seo_description' => $this->faker->text
        ];
    }
}
