<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
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


    public function getDetail(User $user, Group $group) {
        return $user->id == $group->user_id;
    }

    public function update(User $user, Group $group) {
        return $user->id == $group->user_id;
    }

    public function delete(User $user, Group $group) {
        return $user->id == $group->user_id;
    }
}
