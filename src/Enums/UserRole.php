<?php
/**
 * Webstatt
 *
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://webstatt.org
 * @website https://github.com/basteyy/webstatt
 * @license CC BY-SA 4.0
 */

declare(strict_types=1);

namespace basteyy\Webstatt\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';
    case ANONYMOUS = 'visitor';

    /**
     * Check, if $role is a valid UserRole
     * @param string $role
     * @return bool
     */
    public function isValid(string $role) : bool {
        return $role === self::SUPER_ADMIN->value || $role === self::ADMIN->value || $role === self::USER->value || $role === self::ANONYMOUS;
    }

    /**
     * Compare current state against other UserRole
     * @param UserRole $userRole
     * @return bool
     */
    public function isSameOrHigher(UserRole $userRole) : bool {

        if($this === self::SUPER_ADMIN || $this->isSame($userRole)) {
            return true;
        }

        if($userRole === self::USER && $this === self::ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Check, if $userRole is the same as the current state
     * @param UserRole $userRole
     * @return bool
     */
    public function isSame(UserRole $userRole) : bool {
        return $userRole === $this;
    }

    /**
     * Get the Name of the current UserRole state
     * @return string
     */
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