<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuhtResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\BasicResource;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        $token = $user->createToken("Personal Access Token")->accessToken;

        event(new Registered($user));

        return new AuhtResource(true, $user, $token, __('auth.register_success'));
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return new BasicResource(false, __('auth.failed'));
        }

        // if (Auth::user()->email_verified_at == null && Auth::user()->status == 'in_active') {
        //     return new BasicResource(false, __('auth.email_not_verified'));
        // }

        $user = Auth::user();
        $token = $user->createToken("Personal Access Token")->accessToken;

        sendFireBaseNotification($user, __('auth.login_success'));

        return new AuhtResource(true, $user, $token, __('auth.login_success'));
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return new BasicResource(true, __('auth.logout_success'), 'message');
    }
}
