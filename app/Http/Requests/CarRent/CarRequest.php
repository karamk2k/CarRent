<?php

namespace App\Http\Requests\CarRent;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Color;


class CarRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'color' => ['required', new Color()],
            'year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', 'exists:category,id'],
            'transmissions' => ['required', 'in:manual,automatic'],
            'seats' => ['required', 'integer', 'min:1', 'max:20'],
            'fuel_type' => ['required', 'in:petrol,diesel,electric,hybrid'],
            'fuel_capacity' => ['required', 'numeric', 'min:1'],

        ];

        // Add image validation only for create operation
        if ($this->isMethod('POST') && !$this->route('car')) {
            $rules['image'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']; // 2MB max
        } else {
            $rules['image'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048']; // Optional for updates
        }

        return $rules;
    }
}
