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

namespace basteyy\Webstatt\Helper;

use basteyy\Webstatt\Enums\UserRole;

class AdminNavbarItem
{
    private string $url;
    private string $value;
    private string $title;
    private UserRole $minimumUserRole;
    private UserRole $exactUserRole;

    /**
     * Define which target url the item will have
     * @param string $url
     * @return $this
     */
    public function addUrl(string $url): AdminNavbarItem
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Add the value (this is what you see in your browser)
     * @param string $value
     * @return $this
     */
    public function addValue(string $value): AdminNavbarItem
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Add the title-tag value
     * @param string $title
     * @return $this
     */
    public function addTitle(string $title): AdminNavbarItem
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Define, which exact user-role is required to see the item.
     * @param UserRole $userRole
     * @return $this
     */
    public function addExactUserRole(UserRole $userRole) : AdminNavbarItem
    {
        $this->exactUserRole = $userRole;

        return $this;
    }


    /**
     * Define, which minimum user-role is required to see the item
     * @param UserRole $userRole
     * @return $this
     */
    public function addMinimumUserRole(UserRole $userRole) : AdminNavbarItem
    {
        $this->minimumUserRole = $userRole;

        return $this;
    }

    public function getStringIfAllowed(UserRole $userRole) : string {

        if(!isset($this->exactUserRole) && !isset($this->minimumUserRole)) {
            return (string) $this;
        }

        if(isset($this->exactUserRole) && $userRole->isSame($this->exactUserRole)) {
            return (string) $this;
        }

        if(isset($this->minimumUserRole) && $userRole->isSameOrHigher($this->minimumUserRole)) {
            return (string) $this;
        }

        return '';
    }

    public function __toString(): string
    {
        return sprintf(
            '<li class="nav-item"><a class="nav-link" href="%1$s" title="%3$s">%2$s</a></li>',

            $this->url,
            $this->value ?? $this->title ?? $this->url,
            $this->title ?? $this->value ?? $this->url
        );
    }

}