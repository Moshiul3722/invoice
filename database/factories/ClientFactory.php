<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name(),
            'username'=>$this->faker->unique()->username,
            'email'=>$this->faker->unique()->safeEmail(),
            'phone'=>$this->faker->phoneNumber(),
            'user_id'=>User::all()->random()->id,
            'thumbnail'=>'https://picsum.photos/300?randdom='.rand(1,22424),
            'country'=>$this->faker->country(),
        ];
    }
}
