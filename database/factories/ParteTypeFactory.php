<?php

namespace Database\Factories;

use Legislateiro\Models\ParteType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ParteTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ParteType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => Str::random(10),
        ];
    }
}
