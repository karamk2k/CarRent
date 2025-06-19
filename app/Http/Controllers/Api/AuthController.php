<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Auth\UserFavoriteItemResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\AddToFavoriteRequest;
use App\Http\Requests\Auth\RemoveFavortie;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ProfilePictureRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Hash;
use ILLuminate\Support\Facades\Auth;

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
    $credentials = $loginRequest->validated();

    $user = User::where('email', $credentials['email'])->first();

 if (! $user || ! Hash::check($credentials['password'], $user->password)) {
    return $this->apiResponse(false, 'Invalid email or password.', [], 401);
}


    Auth::guard('web')->login($user);

    event(new Login('web', $user, false));

    return $this->apiResponse(true, 'User logged in successfully', new UserResource($user));
}

public function logout()
{
    auth('web')->logout();
    return $this->apiResponse(true, 'User logged out successfully');

}

public function send_otp(){
    $user = Auth::user();
    $user->otp = otp_generate();
    $user->otp_expires_at = Carbon::now()->addMinutes(5);
    $user->save();

    Mail::to($user->email)->send(new OtpMail($user,$user->otp,$user->otp_expires_at));
    return $this->apiResponse(true, 'OTP sent successfully');
    }



    public function verify_otp(VerifyOtpRequest $verifyOtpRequest){
      $user = Auth::user();
      $user->otp=null;
      $user->otp_expires_at=null;
      $user->email_verified_at = Carbon::now();
      $user->save();
      return $this->apiResponse(true, 'OTP verified successfully');

    }

    public function add_favorite(AddToFavoriteRequest $request)
    {

        $user = Auth::user();
        $user->favorites()->create([
            'car_id' => $request->car_id
        ]);

        return $this->apiResponse(true, 'Added to favorites successfully');
    }

    public function remove_favorite(RemoveFavortie $request)
    {

        $user = Auth::user();

        $deleted = $user->favorites()
            ->where('car_id', $request->car_id)
            ->delete();



        return $this->apiResponse(true, 'Removed from favorites successfully');
    }

    public function get_favorites()
    {
        $user = Auth::user();
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

    public function profile(){
        return view('auth.profile');
    }

    public function update_profile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());

        return $this->apiResponse(true, 'Profile updated successfully', new UserResource($user));
    }
    public function change_password(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->apiResponse(true, 'Password changed successfully');
    }

        public function add_profile_picture(ProfilePictureRequest $request)
        {
            $user = Auth::user();

            if ($request->hasFile('profile_picture')) {
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
            } elseif (!$user->profile_picture) {

                $user->profile_picture = 'defaults/images.png';
            }

            $user->save();

            return $this->apiResponse(true, 'Profile picture updated successfully', []);
        }

}

