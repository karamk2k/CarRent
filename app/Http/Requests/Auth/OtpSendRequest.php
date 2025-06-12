<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Carbon\Carbon;

class OtpSendRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('email', $this->input('email'))->first();
            if(isset($user->email_verified_at)){
                $validator->errors()->add('email', 'Email already verified');
            }
            if($user->otp_expires_at > Carbon::now()){
                $validator->errors()->add('email', 'OTP already sent');
            }
            
        });
    }
}
