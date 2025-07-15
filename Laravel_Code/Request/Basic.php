<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules():array{
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|file|mimes:png,jpeg,jpg,pdf|max:2048' 
        ];
    }

     return [
        'title' => 'required|string',
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id',
        'category_id' => 'required|exists:categories,id',
        'images.*' => 'required|file|mimes:png,jpeg,jpg,pdf|max:2048' // Note the 'images.*' for array
    ];
}