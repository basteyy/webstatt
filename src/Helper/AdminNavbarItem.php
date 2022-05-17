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
use function basteyy\VariousPhpSnippets\varDebug;

class AdminNavbarItem
{
    /** @var string Url of the link */
    private string $url;

    /** @var string Value of the link */
    private string $value;

    /** @var string Title Tag of the link */
    private string $title;

    /** @var UserRole Minimum User Role to show the link */
    private UserRole $minimumUserRole = UserRole::USER;

    /** @var UserRole Exact User Role to show the link */
    private UserRole $exactUserRole;

    /** @var array Added child NavBarItems */
    private array $childs = [];

    /** @var bool Is a child-state */
    private bool $child = false;

    /**
     * Set the child state to true or false
     * @return $this
     */
    public function setChild(bool $state = true): self
    {
        $this->child = $state;

        return $this;
    }

    /**
     * Add a Child AdminNavbarItem
     * @param AdminNavbarItem $item
     * @return $this
     */
    public function addChild(AdminNavbarItem $item): self
    {
        $item->setChild();
        $this->childs[] = $item;

        return $this;
    }

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
    public function addExactUserRole(UserRole $userRole): AdminNavbarItem
    {
        $this->exactUserRole = $userRole;

        return $this;
    }


    /**
     * Define, which minimum user-role is required to see the item
     * @param UserRole $userRole
     * @return $this
     */
    public function addMinimumUserRole(UserRole $userRole): AdminNavbarItem
    {
        $this->minimumUserRole = $userRole;

        return $this;
    }

    public function getStringIfAllowed(UserRole $userRole): string
    {

        if (!isset($this->exactUserRole) && !isset($this->minimumUserRole)) {
            return (string)$this;
        }

        if (isset($this->exactUserRole) && $userRole->isSame($this->exactUserRole)) {
            return (string)$this;
        }

        if (isset($this->minimumUserRole) && $userRole->isSameOrHigher($this->minimumUserRole)) {
            return (string)$this;
        }

        return '';
    }

    /**
     * Get all Child NavbarItems as a string
     * @return string
     */
    private function getChilds(): string
    {
        $children = '';
        foreach ($this->childs as $child) {
            $children .= (string)$child;
        }

        return $children;
    }

    /**
     * Get the NavbarItem
     * @return string
     */
    public function __toString(): string
    {
        if (count($this->childs) > 0) {

            return sprintf('<li class="nav-item dropdown mx-lg-3"><a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="%1$s" title="%3$s">%2$s</a><ul class="dropdown-menu p-md-4" aria-labelledby="navbarDropdown"><li><a class="dropdown-item py-md-3" href="%1$s" title="%3$s">%2$s</a></li> %4$s</ul>',
                $this->url,
                $this->value ?? $this->title ?? $this->url,
                htmlspecialchars($this->title ?? $this->value ?? $this->url),
                $this->getChilds()
            );

        } else {

            return sprintf(
                '<li class="%4$s"><a class="%5$s" href="%1$s" title="%3$s">%2$s</a></li>',
                $this->url,
                $this->value ?? $this->title ?? $this->url,
                htmlspecialchars($this->title ?? $this->value ?? $this->url),
                $this->child ? 'nav-item mx-lg-3' : '',
                $this->child ? '' : 'dropdown-item py-md-3'
            );

        }

    }

}