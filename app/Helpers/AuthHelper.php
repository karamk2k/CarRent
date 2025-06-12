<?php 
use App\Models\User;


if (!function_exists('generate_token')) {
    function generate_token(User $user): string
    {
        return $user->createToken($user->name . '-AuthToken')->plainTextToken;
    }
}

if (!function_exists('otp_generate')) {
    function otp_generate(): string
    {
        return rand(1000, 9999);
    }
}

if (!function_exists('is_valid_image')) {
    function is_valid_image($file): bool
    {
        if (!$file instanceof \Illuminate\Http\UploadedFile) {
            return false;
        }

        return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
    }
}
