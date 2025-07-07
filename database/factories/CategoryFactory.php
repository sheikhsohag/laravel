<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'=>User::factory(),
            'title'=>$this->faker->sentence(3),
            'slug'=>fake()->unique()->sentence(1),
            'description'=>fake()->sentence(5),
            'image'=>fake()->address(),
        ];
    }
}


