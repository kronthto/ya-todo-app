<?php

namespace App\Http\Repositories;

use App\User;
use Illuminate\Support\Collection;

class TodoRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @param  User  $user
     * @return Collection
     */
    public function forUser(User $user)
    {
        return $user->todos()
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
