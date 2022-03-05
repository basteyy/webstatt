<?php
declare(strict_types=1);

namespace basteyy\Webstatt\Enums;

enum ContentType: string
{
    case MARKDOWN = 'MARKDOWN';
    case HTML_PHP = 'HTML_PHP';
}