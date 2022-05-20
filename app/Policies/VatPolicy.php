<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vat;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VatPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Vat $vat)
    {
        return $user->id === $vat->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Vat $vat)
    {
        return $user->id === $vat->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
