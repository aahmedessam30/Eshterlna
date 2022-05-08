<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Item $item)
    {
        return $user->id === $item->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Item $item)
    {
        return $user->id === $item->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
