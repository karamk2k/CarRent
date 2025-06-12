<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Carbon\Carbon;

class VerifyOtpRequest extends FormRequest
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
            "otp" => ["required", "digits:4"],
            "email" => ["required", "email", "exists:users"],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('email', $this->input('email'))->first();
            if($user->otp != $this->input('otp')){
                $validator->errors()->add('otp', 'Invalid OTP');
            }
            if($user->otp_expires_at < Carbon::now()){
                $validator->errors()->add('otp', 'OTP expired');
            }
            
        });
    }
}
