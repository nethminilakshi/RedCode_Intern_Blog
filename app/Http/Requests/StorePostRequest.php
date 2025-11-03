<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least 3 characters.',
            'content.required' => 'Content is required.',
            'content.min' => 'Content must be at least 10 characters.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Category does not exist.',
            'tags.*' => 'nullable|string|max:50' 

        ];
    }
}