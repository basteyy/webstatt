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
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use function basteyy\VariousPhpSnippets\varDebug;

final class PagesModel extends Model
{
    protected string $database_name = 'pages';

    /**
     * @throws IOException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     */
    public function findOneByUrl(string $url, bool $use_cache = true): PageEntity|null
    {
        return $this->_findByOneArgument('url', '=', $url, false, true, $use_cache);
    }

    /**
     * Search for one/or all, where search_field is search_value.
     * @param string $search_field Field which is searched
     * @param string $operator Operator
     * @param mixed $search_value Value of search
     * @param bool $multiple_results Return all results or just one
     * @param bool $create_entities Create the entity directly
     * @return array|null|PageEntity
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    private function _findByOneArgument(
        string $search_field,
        string $operator,
        mixed  $search_value,
        bool   $multiple_results = false,
        bool   $create_entities = true,
        bool   $use_cache = true
    ): array|null|PageEntity
    {
        $key = hash('xxh3', $search_field . $operator . $search_value);

        if ($use_cache && APCU_SUPPORT && apcu_exists($key)) {
            $data = apcu_fetch($key);
        } else {
            $data = $multiple_results ? $this->getRaw()->findBy([$search_field, $operator, $search_value]) : $this->getRaw()->findOneBy([$search_field, $operator, $search_value]);
        }

        if (!$data) {
            return null;
        }

        if (APCU_SUPPORT) {
            apcu_add($key, $data, APCU_TTL_LONG);
        }

        return $create_entities ? $this->createEntities($data) : $data;
    }

    /**
     * @throws IOException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     */
    public function findOneBySecret(string $secret, bool $use_cache = true): PageEntity|null
    {
        return $this->_findByOneArgument('secret', '=', $secret, false, true, $use_cache);
    }

    /**
     * @throws IOException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException|ReflectionException
     */
    public function getAll(): array
    {
        $entries = $this->getRaw()->findAll();
        return 0 < count($entries) ? $this->createEntities($entries) : [];
    }

    /**
     * @throws IOException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     */
    public function getAllOnlinePages(bool $use_cache = true): array
    {
        return $this->_findByOneArgument('online', '=', true, true, true, $use_cache) ?? [];
    }
}