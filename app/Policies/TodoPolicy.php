<?php

namespace App\Policies;

use App\Todo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can update the given task.
     *
     * @param  User  $user
     * @param  Todo  $task
     * @return bool
     */
    public function update(User $user, Todo $task)
    {
        return $user->id === $task->user_id;
    }
}
