<?php
declare(strict_types=1);

namespace basteyy\Webstatt\Enums;

enum ContentType: string
{
    case MARKDOWN = 'markdown';
    case HTML_PHP = 'html';
}