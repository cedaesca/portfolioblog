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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => ['required', 'string', Rule::enum(PostType::class)],
            'is_published' => 'sometimes|boolean',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($this->route('slug'), 'slug')
            ]
        ];
    }
}