<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SizePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Size $size)
    {
        return $user->id === $size->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Size $size)
    {
        return $user->id === $size->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
