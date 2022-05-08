<?php

namespace App\Policies;

use App\Models\Color;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ColorPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Color $color)
    {
        return $user->id === $color->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Color $color)
    {
        return $user->id === $color->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
