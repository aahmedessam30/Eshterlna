<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Resources\BasicResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Helpers;

class VerifyEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    // We Must Send Token That In Register Response With Verification Link
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return new BasicResource(true, __('auth.email_already_verified'), 'message');
        }

        $request->fulfill();
        $request->user()->update(['status' => 'active']);

        return new BasicResource(true, __('auth.email_verified'), 'message');
    }

    // Resend Verification Email
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return new BasicResource(true, __('auth.email_verification_link_sent'), 'message');
    }
}
