<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserFavorite;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Auth\UserFavoriteItemResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpSendRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\AddToFavoriteRequest;
use App\Http\Requests\Auth\RemoveFavortie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;


class AuthController extends Controller
{
    public function store(RegisterRequest $registerRequest)
    {
        $role = Role::firstOrCreate(['name' => 'customer', 'description' => 'Customer role']);
        $user = User::create(array_merge(
            $registerRequest->validated(),
            ['role_id' => $role->id]
        ));
        return $this->apiResponse(true, 'User created successfully', new UserResource($user));
    }

   public function login(LoginRequest $loginRequest)
{
    if (!$token = auth()->attempt($loginRequest->validated())) {
        return $this->apiResponse(false, 'Unauthorized', 401);
    }

    $user = auth()->user();
    

    return $this->apiResponse(
        true, 
        'User logged in successfully', 
        new UserResource($user)
    );
}

public function logout()
{
    auth()->logout();   
    return $this->apiResponse(true, 'User logged out successfully');

}

public function send_otp(OtpSendRequest $otpSendRequest){
    $user = User::where('email', $otpSendRequest->input('email'))->first();
    $user->otp = otp_generate();
    $user->otp_expires_at = Carbon::now()->addMinutes(5);
    $user->save();

    Mail::to($user->email)->send(new OtpMail($user,$user->otp,$user->otp_expires_at));
    return $this->apiResponse(true, 'OTP sent successfully');
    }



    public function verify_otp(VerifyOtpRequest $verifyOtpRequest){
      $user = User::where('email', $verifyOtpRequest->input('email'))->first();
      $user->otp=null;
      $user->otp_expires_at=null;
      $user->save();
      return $this->apiResponse(true, 'OTP verified successfully');

    }

    public function add_favorite(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);

        $user = auth()->user();
        
        // Check if already favorited
        if ($user->favorites()->where('car_id', $request->car_id)->exists()) {
            return $this->apiResponse(false, 'Car is already in favorites');
        }

        // Add to favorites
        $user->favorites()->create([
            'car_id' => $request->car_id
        ]);

        return $this->apiResponse(true, 'Added to favorites successfully');
    }

    public function remove_favorite(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id'
        ]);

        $user = auth()->user();
        
        // Find and delete the favorite
        $deleted = $user->favorites()
            ->where('car_id', $request->car_id)
            ->delete();

        if (!$deleted) {
            return $this->apiResponse(false, 'Car was not in favorites');
        }

        return $this->apiResponse(true, 'Removed from favorites successfully');
    }

    public function get_favorites()
    {
        $user = auth()->user();
        $favorites = $user->favorites()
            ->with(['car' => function($query) {
                $query->with('categoryRes');
            }])
            ->get();

        return $this->apiResponse(
            true, 
            'Favorites retrieved successfully', 
            UserFavoriteItemResource::collection($favorites)
        );
    }

}

