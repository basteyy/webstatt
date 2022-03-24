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

use basteyy\Webstatt\Models\Entities\PageEntity;
use ReflectionException;

final class SnippetsModel extends Model
{
    protected string $database_name = 'snippets';


    /**
     * @throws ReflectionException
     */
    public function findOneByKey(string $key, bool $use_cache = true): PageEntity|null
    {
        return $this->_findByOneArgument('key', '=', $key, false, true, $use_cache);
    }

}