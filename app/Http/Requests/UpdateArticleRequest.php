<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'article' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,approved,published,rejected',
            'tags' => 'sometimes|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
} 