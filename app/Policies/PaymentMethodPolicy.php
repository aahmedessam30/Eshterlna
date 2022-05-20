<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PaymentMethodPolicy
{
    use HandlesAuthorization;

    public function update(User $user, PaymentMethod $paymentMethod)
    {
        return $user->id === $paymentMethod->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, PaymentMethod $paymentMethod)
    {
        return $user->id === $paymentMethod->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
