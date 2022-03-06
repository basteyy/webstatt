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

use basteyy\MinimalHashWrapper\MinimalHashWrapper;

class UserPasswordStrategy
{
    public static function getHash(string $password, string $salt): string
    {
        return MinimalHashWrapper::getHash($salt . $password . $salt);
    }

    public static function comparePassword(string $hash, string $password, string $salt): bool
    {
        return MinimalHashWrapper::compare($salt . $password . $salt, $hash);
    }
}