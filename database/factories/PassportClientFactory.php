<?php

namespace Database\Factories;


/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Laravel\Passport\Client;

use Legislateiro\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PassportClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'name' => $this->faker->company,
            'secret' => Str::random(40),
            'redirect' => $this->faker->url,
            'personal_access_client' => false,
            'password_client' => false,
            'revoked' => false,
        ];
    }
    
    /**
     * Indicate that the user is suspended.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPasspord()
    {
        // $factory->state(Client::class, 'password_client', function (Faker $faker) {
        //     return [
        //         'personal_access_client' => false,
        //         'password_client' => true,
        //     ];
        // });
        return $this->state(function (array $attributes) {
            return [
                'personal_access_client' => false,
                'password_client' => true,
            ];
        });
    }
}


