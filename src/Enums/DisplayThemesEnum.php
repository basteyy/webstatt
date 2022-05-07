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

/**
 * The Access Types is a helper Enum to easily work with access.ini
 * @see https://github.com/basteyy/webstatt/wiki/Access-Managament
 */

enum DisplayThemesEnum: string
{
    case DARK = 'dark';
    case LIGHT = 'light';
}