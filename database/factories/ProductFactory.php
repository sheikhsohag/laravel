<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(){
        return [
            'title'=>fake()->sentence(1),
            'user_id'=>User::Factory(),
            'category_id'=>Category::Factory(),
            'image'=>fake()->filePath(),
            'description'=>fake()->sentence(3)
        ];
    }
}