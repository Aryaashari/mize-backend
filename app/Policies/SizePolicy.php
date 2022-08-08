<?php

namespace App\Policies;

use App\Models\Size;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SizePolicy
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

    public function getDetail(User $user, Size $size) {
        return $user->id === $size->user_id;
    }

    public function update(User $user, Size $size) {
        return $user->id === $size->user_id;
    }

    public function delete(User $user, Size $size) {
        return $user->id === $size->user_id;
    }
}
