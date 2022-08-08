<?php

namespace App\Policies;

use App\Models\Priority;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PriorityPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function getDetail(User $user, Priority $priority) {
        return $user->id === $priority->size->user_id;
    }

    public function update(User $user, Priority $priority) {
        return $user->id === $priority->size->user_id;
    }

    public function delete(User $user, Priority $priority) {
        return $user->id === $priority->size->user_id;
    }
}
