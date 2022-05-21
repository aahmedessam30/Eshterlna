<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\BasicResource;

class UserController extends Controller
{
    public function profile()
    {
        return (new UserResource(Auth::user()))->additional(['status' => true]);
    }

    public function editProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->safe()->all());

        // Send Notification For Authorized User
        sendFireBaseNotification(Auth::user(), __('notification.profile_updated'));

        return (new UserResource(Auth::user()))->additional(['status' => true, 'message' => __('auth.edit_profile_success')]);
    }
}
