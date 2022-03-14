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

class PageEntity extends Entity implements EntityInterface
{
    protected string $url;
    protected string $title;
    protected string $name;
    protected string $description;
    protected string $keywords;
    protected string $path;
    protected bool $online;
    protected string $secret;
    protected string $layout;
    protected PageType $pageType;

    /** @var string $cache_key_name Key for storing the page in cache */
    public string $cache_key_name;

    /** @var PageStorageHelper $pageStorageHelper Access to the file system */
    protected PageStorageHelper $pageStorageHelper;

    /**
     * Constructer creates the $cache_key_name
     * @param array $data
     * @param string $primary_id_name
     * @throws \ReflectionException
     */
    public function __construct(array $data, string $primary_id_name)
    {
        parent::__construct($data, $primary_id_name);
        $this->cache_key_name = hash('xxh3', 'page_' . $this->getId());
    }

    /**
     * Checks, if the page has a valid layout (valid means a valid string)
     * @return bool
     */
    public function hasLayout(): bool
    {
        return strlen($this->layout) > 0 && 'NONE' !== $this->layout;
    }

    /**
     * Return a instance of the Storage Helper for the current page
     * @return PageStorageHelper
     */
    public function getStorage() : PageStorageHelper {
        if(!isset($this->pageStorageHelper)) {
            $this->pageStorageHelper = new PageStorageHelper($this);
        }

        return $this->pageStorageHelper;
    }

    /**
     * Get the parsed markdown body
     * @param bool $from_cache_allowed
     * @return string
     */
    public function getMarkdownParsedBody(bool $from_cache_allowed = true): string
    {
        $parsed_cache_name = $this->cache_key_name . 'parsed_body';

        if(APCU_SUPPORT && $from_cache_allowed && apcu_exists($parsed_cache_name)) {
            return apcu_fetch($parsed_cache_name) . PHP_EOL . '<!-- Cached Version from ' . date('d.m.Y H:i:s', apcu_key_info($parsed_cache_name)['creation_time']) . ' -->';
        }

        $parsed_body = (new \Parsedown())->parse($this->getStorage()->getBody($from_cache_allowed));

        if(APCU_SUPPORT) {
            apcu_add($parsed_cache_name, $parsed_body, APCU_TTL_LONG);
        }

        return $parsed_body;
    }
}