<?php

namespace App\Enum;

enum UserRoleEnum
{
    case ROLE_USER;
    case ROLE_ADMIN;

    public static function defaultRoleEnum(): UserRoleEnum
    {
        return self::ROLE_USER;
    }

    public static function defaultRole(): string
    {
        return self::defaultRoleEnum()->name;
    }

    public static function exists(string $name): bool
    {
        foreach (self::cases() as $role) {
            if ($role->name === $name) {
                return true;
            }
        }
        return false;
    }
}
