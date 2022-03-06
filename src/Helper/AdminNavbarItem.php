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

class AdminNavbarItem
{
    private string $url;
    private string $value;
    private string $title;

    public function addUrl(string $url): AdminNavbarItem
    {
        $this->url = $url;
        return $this;
    }

    public function addValue(string $value): AdminNavbarItem
    {
        $this->value = $value;
        return $this;
    }

    public function addTitle(string $title): AdminNavbarItem
    {
        $this->title = $title;
        return $this;
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