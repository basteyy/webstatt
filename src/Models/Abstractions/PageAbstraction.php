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

namespace basteyy\Webstatt\Models\Abstractions;

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Services\ConfigService;
use DirectoryIterator;
use Exception;
use JetBrains\PhpStorm\Pure;
use Parsedown;
use SplFileInfo;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\slugify;

final class PageAbstraction
{
    private string $path;
    private string $title;
    private string $url;
    private string $description;
    private string $keywords;
    private string $body;
    private string $secret;
    private string $layout;
    private PageType $pageType;
    private bool $online;
    private ConfigService $configService;
    /**
     * @var int
     */
    private int $id = -1;

    private mixed $name;

    private bool $_new;

    private string $backup_folder_name = '.versions' . DIRECTORY_SEPARATOR;
    private string $file_name = 'content';
    private string $file_markdown_extension = 'md';
    private string $file_html_php_extension = 'php';

    /** @var string $hash Hash used as key in APCu */
    private string $hash;

    /**
     * @param array $data
     * @param ConfigService|null $configService
     * @throws Exception
     */
    public function __construct(array $data, ConfigService $configService = null)
    {
        if (isset($configService)) {
            $this->configService = $configService;
        }

        if (!$data['title']) {
            throw new Exception(__('A title tag is required to build a page!'));
        }

        if (!$data['path']) {
            if (!isset($configService)) {
                throw new Exception(__('To create a new page, you need to provide the configService to the PageAbstraction-Class.'));
            }

            $this->configService = $configService;
            $storage_location = ROOT . rtrim($this->configService->pages_private_folder, '/') . DIRECTORY_SEPARATOR;

            if (!is_dir($storage_location)) {
                mkdir($storage_location, 0755, true);
            }

            $data['path'] = $storage_location . slugify(basename($data['title'], '.php'));

        }

        $this->_new = file_exists($data['path']);

        $this->path = $data['path'];
        $this->layout = $data['layout'] ?? '';
        $this->name = $data['name'] ?? $data['title'];
        $this->title = $data['title'];
        $this->url = isset($data['url']) ? slugify($data['url']) : '';
        $this->secret = $data['secret'] ?? getRandomString(16);
        $this->description = $data['description'] ?? '';
        $this->keywords = $data['keywords'] ?? '';
        $this->body = $data['body'] ?? '';
        $this->pageType = !isset($data['pageType']) ? PageType::MARKDOWN : PageType::tryFrom($data['pageType']) ?? PageType::MARKDOWN;
        $this->online = $data['online'] ?? false;
        $this->id = $data['_id'] ?? -1;


        $this->hash = hash('xxh3', $this->getUrl());
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function __set(string $name, $value): void
    {
        if (!isset($this->{$name})) {
            throw new Exception(sprintf('Unknown attribute %s', $name));
        }

        $this->{$name} = $value;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function hasLayout(): bool
    {
        return strlen($this->layout) > 0 && 'NONE' !== $this->layout;
    }

    public function getParsedBody(): string
    {
        if (APCU_SUPPORT) {

            if (!apcu_exists($this->hash)) {
                apcu_add($this->hash, (new Parsedown())->parse($this->loadBody()), APCU_TTL_LONG);
            }

            return apcu_fetch($this->hash) . PHP_EOL . '<!-- Cached Version from ' . date('d.m.Y H:i:s', apcu_key_info($this->hash)['creation_time']) . ' -->';
        }

        return (new Parsedown())->parse($this->loadBody());
    }



    /**
     * Change the current page content context to $changepageType
     * @param PageType $changeTopageType
     * @return void
     */
    public function changepageTypeTo(PageType $changeTopageType): void
    {
        $versions = $this->getAllVersions();

        $new_extension = '.' . ($changeTopageType === PageType::MARKDOWN ? $this->file_html_php_extension : $this->file_markdown_extension);

        foreach ($versions as $timestamp => $path) {
            copy($path, str_replace($this->getFileExtension(true), $new_extension, $path));
            unlink($path);
        }

        // Change current main version
        $old = $this->getAbsoluteFilePath();
        copy($this->getAbsoluteFilePath(), str_replace($this->getFileExtension(true), $new_extension, $this->getAbsoluteFilePath()));
        unlink($old);

        // Flush the Cache
        $this->flushCache();

    }

    /**
     * Delete current cache von apcu
     * @return void
     */
    public function flushCache()
    {
        if (APCU_SUPPORT && apcu_exists($this->hash)) {
            apcu_delete($this->hash);
        }
    }

    /**
     * Build the array for storing it
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'name'        => $this->name,
            'url'         => $this->url,
            'description' => $this->description,
            'keywords'    => $this->keywords,
            'path'        => $this->path,
            'online'      => $this->online,
            'secret'      => $this->secret,
            'layout'      => $this->layout,
            'pageType' => $this->pageType
        ];
    }

}