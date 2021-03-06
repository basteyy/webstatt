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

enum PageType: string
{
    case MARKDOWN = 'MARKDOWN';
    case HTML_PHP = 'HTML_PHP';


    /**
     * Return the corresponding file extension
     * @return string
     */
    public function fileExtension() : string {
        return match($this) {
            self::MARKDOWN => 'md',
            self::HTML_PHP => 'php'
        };
    }
}