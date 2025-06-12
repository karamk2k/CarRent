<?php

namespace App\Http\Requests\CarRent;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ActiveDiscount;
use App\Rules\ActiveCar;

class CreateRentalRequest extends FormRequest
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
            'car_id' => ['required', 'exists:cars,id', new ActiveCar],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'discount_name' => ['nullable', 'exists:discounts,name', new ActiveDiscount],
            'payment_method' => ['required', 'in:stripe']
        ];
    }
}
