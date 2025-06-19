<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CurrentPassword;
use Illuminate\Validation\Rules\Password;



class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required', 'string', new CurrentPassword()],
            "new_password" => ['required', password::min(8)->letters()->numbers()->symbols()->mixedCase()],
            "new_password_confirmation" => "same:new_password",
        ];
    }
}
