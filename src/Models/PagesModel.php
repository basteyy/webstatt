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
     * @param bool $use_cache
     * @return array|PageEntity
     * @throws ReflectionException
     */
    public function getStartpage(bool $use_cache = false) : null|PageEntity
    {
        if($use_cache && APCU_SUPPORT && apcu_exists(W_PAGES_STARTPAGE_CACHE_KEY)) {
            return $this->createEntities( apcu_fetch(W_PAGES_STARTPAGE_CACHE_KEY));
        }

        $entity = $this->_findByArgumentsArray(
            [
                ['startpage', '=', true], ['online', '=', true]
            ]
        );

        if($use_cache && APCU_SUPPORT) {
            apcu_add(W_PAGES_STARTPAGE_CACHE_KEY, $entity, APCU_TTL_LONG);
        }

        if($entity) {
            return $this->createEntities($entity);
        }

        return null;
    }

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
        $pages = $this->_findByOneArgument('online', '=', true, true, true, $use_cache);

        if(!is_array($pages)) {
            return [$pages];
        }

        return  $pages ?? [];
    }
}