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

namespace basteyy\Webstatt\Models\Entities;

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Helper\PageStorageHelper;
use JetBrains\PhpStorm\Pure;
use function basteyy\VariousPhpSnippets\varDebug;

class SnippetEntity extends Entity implements EntityInterface
{
    protected string $name;
    protected string $key;
    protected string $content;


}