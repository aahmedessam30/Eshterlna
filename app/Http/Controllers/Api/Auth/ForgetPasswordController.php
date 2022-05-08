<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasicResource;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;

class ForgetPasswordController extends Controller
{
    public function forget_password(ForgetPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? new BasicResource(true, __('auth.forget_password_link_sent'), 'message')
            : new BasicResource(false, __('auth.forget_password_link_sent_failed'), 'message');
    }

    public function reset_password(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->validated(),
            function ($user, $password) {
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? new BasicResource(true, __('auth.reset_password_success'), 'message')
            : new BasicResource(false, __('auth.reset_password_failed'), 'message');
    }
}
