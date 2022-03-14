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

enum ConfigFile: string
{
    case MAIL = 'mail_config';


    public function cacheName() : string
    {
        return match ($this) {
            self::MAIL => 'mail_config_cache'
        };
    }

}