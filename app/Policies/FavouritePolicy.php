<?php

namespace App\Policies;

use App\Models\Favourite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FavouritePolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Favourite $favourite)
    {
        return $user->id === $favourite->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
