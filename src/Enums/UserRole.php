<?php
declare(strict_types=1);

namespace basteyy\Webstatt\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';
    case ANONYMOUS = 'visitor';

    public function isValid(string $role) : bool {
        return $role === self::SUPER_ADMIN->value || $role === self::ADMIN->value || $role === self::USER->value || $role === self::ANONYMOUS;
    }

    public function getTitle() : string {
        return match($this)
        {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::USER => 'Nutzer',
            self::ANONYMOUS => 'Besucher'
        };
    }
}