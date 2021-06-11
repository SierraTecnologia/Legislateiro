<?php

namespace Database\Factories;

use Legislateiro\Models\TermType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TermTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TermType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->name,
            'name' => $this->faker->name,
            'description' => Str::random(10),
        ];
    }
}
