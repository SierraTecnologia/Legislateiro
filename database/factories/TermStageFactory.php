<?php

namespace Database\Factories;

use Legislateiro\Models\TermStage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TermStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TermStage::class;

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
