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

namespace basteyy\Webstatt\Models\Entities;

use basteyy\Webstatt\Enums\UserRole;
use JetBrains\PhpStorm\Pure;

class UserEntity extends Entity implements EntityInterface
{
    protected string $email;
    protected string $password;
    protected string $salt;
    protected UserRole $role;
    protected string $secret;
    protected string $name = '';
    protected string $alias = '';

    public function hasName() : bool {
        return isset($this->name) && '' !== $this->name;
    }

    public function hasEmail () : bool {
        return isset($this->email) && '' !== $this->email;
    }

    public function hasAlias () : bool {
        return isset($this->alias) && '' !== $this->alias;
    }

    /**
     * Return a name, alias, email or the user-id in cases, you want to display any stringed ID of the current entity
     * @return string
     */
    #[Pure] public function getAnyName() : string {
        if($this->hasName()) {
            return $this->name;
        }
        if($this->hasAlias()) {
            return $this->alias;
        }
        if($this->hasEmail()) {
            return $this->email;
        }
        return 'User-' . $this->getId();
    }

    /**
     * Returns true if the user is not at least a user
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return !$this->isUser();
    }

    /**
     * Return true, if the user is at least user (or above)
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->isAdmin() || $this->getRole() === UserRole::USER;
    }

    /**
     * Return true, if the user is admin or super admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->getRole() === UserRole::ADMIN;
    }

    /**
     * Return true, if the user is  super admin
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->getRole() === UserRole::SUPER_ADMIN;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }
}