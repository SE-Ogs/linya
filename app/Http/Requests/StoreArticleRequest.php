<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'article' => 'required|string',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
} 