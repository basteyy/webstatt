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

use basteyy\Webstatt\Models\Entities\LayoutEntity;
use ReflectionException;

final class LayoutsModel extends Model
{
    protected string $database_name = 'layouts';

    /**
     * @throws ReflectionException
     */
    public function findByName(string $layoutName) : LayoutEntity|null {
        return $this->_findByOneArgument('name', '=', $layoutName);
    }

    public function findBySecret(string $secret_key)
    {
        return $this->_findByOneArgument('secret', '=', $secret_key);
    }
}