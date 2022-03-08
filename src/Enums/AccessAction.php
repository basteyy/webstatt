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

enum AccessAction: string
{
    /** Content Actions */
    case CONTENT_EDIT = 'content_edit';
    case CONTENT_CREATE = 'content_create';
    case CONTENT_DELETE = 'content_delete';

    /** User Actions */
    case USER_EDIT = 'user_edit';
    case USER_CREATE = 'user_create';
    case USER_DELETE = 'user_delete';

    /** File Actions */
    case FILE_EDIT = 'file_edit';
    case FILE_CREATE = 'file_create';
    case FILE_DELETE = 'file_delete';

    /** Settings Actions */
    case SETTINGS_EDIT = 'settings_edit';
}