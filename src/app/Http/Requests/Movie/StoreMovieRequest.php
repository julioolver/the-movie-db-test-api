<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'external_id' => ['required', 'string', 'max:255'],
            'provider' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'director' => ['nullable', 'string', 'max:255'],
            'synopsis' => ['nullable', 'string'],
            'duration' => ['nullable', 'string', 'max:10'],
            'year' => ['nullable', 'string'],
            'rating' => ['nullable', 'string', 'max:255'],
            'poster_path' => ['nullable', 'string', 'max:255'],
            'watched' => ['required', 'boolean'],
            'favorite' => ['required', 'boolean'],
            'watch_later' => ['required', 'boolean'],
        ];
    }
}
