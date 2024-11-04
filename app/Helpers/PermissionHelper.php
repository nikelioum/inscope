<?php

namespace App\Helpers;

use App\Models\User;

class PermissionHelper
{
    // Check if the authenticated user is an admin
    public static function isAdmin(User $user): bool
    {
        return $user->role && $user->role->name === 'admin';
    }
}
