<?php

namespace App\Enum;

use InvalidArgumentException;

enum UserRoleEnum
{
    case ROLE_USER;
    case ROLE_ADMIN;

    /**
     * @return UserRoleEnum
     */
    public static function defaultRoleEnum(): UserRoleEnum
    {
        return self::ROLE_USER;
    }

    /**
     * @return string
     */
    public static function defaultRole(): string
    {
        return self::defaultRoleEnum()->name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function exists(string $name): bool
    {
        foreach (self::cases() as $role) {
            if ($role->name === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $role
     * @return UserRoleEnum
     */
    public static function fromString(string $role): UserRoleEnum
    {
        $cases = self::cases();
        foreach ($cases as $case) {
            if ($role === $case->name) {
                return $case;
            }
        }
        throw new InvalidArgumentException("Role not found");
    }
}
