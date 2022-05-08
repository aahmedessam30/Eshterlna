<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;


class StorePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Store $store)
    {
        return $user->id === $store->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Store $store)
    {
        return $user->id === $store->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
