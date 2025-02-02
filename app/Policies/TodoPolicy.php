<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;

class TodoPolicy
{
    public function isOwner(User $user, Todo $todo)
    {
        return $user->id === $todo->user->id;
    }
}
