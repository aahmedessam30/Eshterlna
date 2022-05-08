<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BrandPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Brand $brand)
    {
        return $user->id === $brand->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Brand $brand)
    {
        return $user->id === $brand->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
