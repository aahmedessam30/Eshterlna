<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Review $review)
    {
        return $user->id === $review->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_update'));
    }

    public function delete(User $user, Review $review)
    {
        return $user->id === $review->user_id
            ? Response::allow()
            : Response::deny(__('messages.cant_delete'));
    }
}
