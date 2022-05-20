<?php

namespace App\Policies;

use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ShippingMethodPolicy
{
    use HandlesAuthorization;

    public function update(User $user, ShippingMethod $shippingMethod)
    {
        return $user->id === $shippingMethod->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, ShippingMethod $shippingMethod)
    {
        return $user->id === $shippingMethod->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
