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

namespace basteyy\Webstatt\Models;

use basteyy\Webstatt\Models\Entities\EntityInterface;
use basteyy\Webstatt\Models\Entities\PageEntity;
use basteyy\Webstatt\Models\Entities\SnippetEntity;
use ReflectionException;

final class SnippetsModel extends Model
{
    protected string $database_name = 'snippets';

    /**
     * @throws ReflectionException
     */
    public function findOneByKey(string $key, bool $use_cache = true): SnippetEntity|null
    {
        return $this->_findByOneArgument('key', '=', $key, false, true, $use_cache);
    }

    public function findOneBySecret(string $secret, bool $use_cache = true): SnippetEntity|null
    {
        return $this->_findByOneArgument('secret', '=', $secret, false, true, $use_cache);
    }

}