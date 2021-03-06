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

use basteyy\Webstatt\Enums\DisplayThemesEnum;
use basteyy\Webstatt\Enums\UserRole;
use JetBrains\PhpStorm\Pure;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getDateTimeFormat;
use function basteyy\VariousPhpSnippets\getNiceDateTimeFormat;

class UserEntity extends Entity implements EntityInterface
{
    protected string $email;
    protected string $password;
    protected string $salt;
    protected UserRole $role;
    protected string $secret;
    protected string $name = '';
    protected string $alias = '';
    protected string $signupIp = '';
    protected string $codemirror_theme = 'nord';
    protected DisplayThemesEnum $displayMode = DisplayThemesEnum::LIGHT;
    protected \DateTime|null $created;
    protected \DateTime|null $lastlogin;

    public function getDisplayTheme() : DisplayThemesEnum {
        return $this->displayMode;
    }
    public function getCodeMirrorTheme() : string {
        return $this->codemirror_theme;
    }

    public function getNiceCreatedDateTime() : string {
        return isset($this->created) ? getDateTimeFormat($this->created) : __('never');
    }
    public function hasName() : bool {
        return isset($this->name) && '' !== $this->name;
    }

    public function hasEmail () : bool {
        return isset($this->email) && '' !== $this->email;
    }

    public function hasAlias () : bool {
        return isset($this->alias) && '' !== $this->alias;
    }

    public function getLastlogin() : string {
        return isset($this->lastlogin) ? getDateTimeFormat($this->lastlogin) : __('never');
    }

    #[Pure] public function getRoleBadge() : string {
        return  $this->getRole() === UserRole::SUPER_ADMIN ?
            '<span class="badge rounded-pill bg-primary">Superadmin</span>' : (
            $this->getRole() === UserRole::ADMIN ?
                '<span class="badge rounded-pill bg-dark text-light">Admin</span>' :
                '<span class="badge rounded-pill bg-light text-dark">User</span>');
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