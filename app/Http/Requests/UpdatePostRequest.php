<?php

namespace App\Http\Requests;

use App\Enums\PostType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string|min:3|max:255',
            'content' => 'string',
            'type' => Rule::enum(PostType::class),
            'is_published' => 'boolean',
            'slug' => [
                'string',
                'min:3',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($this->route('slug'), 'slug')
            ]
        ];
    }
}