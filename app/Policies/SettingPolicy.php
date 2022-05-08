<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Setting $setting)
    {
        return $user->id === $setting->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Setting $setting)
    {
        return $user->id === $setting->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
