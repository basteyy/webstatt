<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Models\Abstractions;

use basteyy\Webstatt\Enums\ContentType;
use basteyy\Webstatt\Services\ConfigService;
use Exception;
use Filebase\Config;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\slugify;
use function basteyy\VariousPhpSnippets\varDebug;

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
    private ContentType $contentType;
    private bool $online;
    private ConfigService $configService;
    /**
     * @var int
     */
    private int $id = -1;

    private mixed $name;


    private string $backup_folder_name = '.versions' . DIRECTORY_SEPARATOR;
    private string $file_name = 'content';
    private string $file_markdown_extension = 'md';
    private string $file_html_php_extension = 'php';


    /**
     * @param array $data
     * @param ConfigService|null $configService
     * @throws Exception
     */
    public function __construct(array $data, ConfigService $configService = null)
    {
        if(isset($configService)) {
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

        $this->path = $data['path'];
        $this->layout = $data['layout'] ?? '';
        $this->name = $data['name'] ?? $data['title'];
        $this->title = $data['title'];
        $this->url = isset($data['url']) ? slugify($data['url']) : '';
        $this->secret = $data['secret'] ?? getRandomString(16);
        $this->description = $data['description'] ?? '';
        $this->keywords = $data['keywords'] ?? '';
        $this->body = $data['body'] ?? '';
        $this->contentType = !isset($data['contentType']) ? ContentType::MARKDOWN : ContentType::tryFrom($data['contentType']) ?? ContentType::MARKDOWN;
        $this->online = $data['online'] ?? false;
        $this->id = $data['_id'] ?? -1;
    }

    public function __set(string $name, $value): void
    {
        if (!isset($this->{$name})) {
            throw new Exception(sprintf('Unknown attribute %s', $name));
        }

        $this->{$name} = $value;
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function getPath(): string
    {
        return $this->path;
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

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function getLayout() : string|null {
        return $this->layout;
    }

    public function getBody(): string
    {
        return $this->loadBody();
    }

    public function getOnline(): bool
    {
        return $this->online;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Return the current file extension based on the content type
     * @param bool $leading_dot
     * @return string
     */
    #[Pure] private function getFileExtension(bool $leading_dot = true) : string {
        return ($leading_dot ? '.' : '') . match ($this->getContentType()) {
            ContentType::HTML_PHP => $this->file_html_php_extension,
            ContentType::MARKDOWN => $this->file_markdown_extension
        };
    }

    /**
     * Return the absolute filepath for the file
     * @return string
     */
    #[Pure] private function getAbsoluteFilePath() : string {
        return $this->getPath() . DIRECTORY_SEPARATOR . $this->file_name . $this->getFileExtension();
    }

    /**
     * Loads the body form file
     * @return string
     */
    private function loadBody(): string
    {
        return file_exists($this->getAbsoluteFilePath()) ? file_get_contents($this->getAbsoluteFilePath()) : '';
    }

    /**
     * Updates the body
     * @param string $body
     * @return void
     */
    public function updateBody(string $body): void
    {
        if ($this->isDifferentBody($body)) {
            $this->makeBackup();
        }

        file_put_contents($this->getAbsoluteFilePath(), $body);
    }

    /**
     * Checks if the new body is different from the old one
     * @param string $new_body
     * @return bool
     */
    private function isDifferentBody(string $new_body)
    {
        return strlen($this->loadBody()) > 0 && hash('xxh3', $new_body) !== hash('xxh3', $this->loadBody());
    }

    /**
     * @throws Exception
     */
    private function makeBackup(): void
    {
        if(!isset($this->configService)) {
            throw new \Exception(__('To use the Versioning, you need to provide the configService to the %s', __CLASS__));
        }

        $folder = $this->path . DIRECTORY_SEPARATOR . $this->backup_folder_name;
        $version_name = 'v_' . date('d_m_H_i_s') . $this->getFileExtension();

        if($this->configService->pages_max_versions !== 0 ) {

            $versions = $this->getAllVersions();

            if($this->configService->pages_max_versions !== -1 && count($versions) >= $this->configService->pages_max_versions) {
                // Delete the oldest version
                unlink($versions[key($versions)]);
            }

            if(!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            file_put_contents($folder . $version_name, $this->loadBody());
        }



    }

    public function getAllVersions() : array {
        $folder = $this->path . DIRECTORY_SEPARATOR . $this->backup_folder_name;

        if(!is_dir($folder)) {
            return [];
        }

        $dir = new \DirectoryIterator($folder);

        foreach($dir as $file) {
            /** @var \SplFileInfo $file */
            if($file->isFile() && $file->getExtension() === $this->getFileExtension(false) ) {
                // Valid look file .. :-)
                $versions[$file->getMTime()] = $file->getRealPath();
            }
        }

        if(!isset($versions)) {
            return [];
        }

        ksort($versions);

        return $versions;

    }

    /**
     * Build the array for storing it
     * @return array
     */
    #[ArrayShape(['title' => "mixed|string", 'name' => "mixed", 'url' => "string", 'description' => "mixed|string", 'keywords' => "mixed|string", 'path' => "mixed|string", 'online' => "bool|mixed", 'secret' => "mixed|string", 'layout' => "mixed|string"])] public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'name'       => $this->name,
            'url'         => $this->url,
            'description' => $this->description,
            'keywords'    => $this->keywords,
            'path'        => $this->path,
            'online'      => $this->online,
            'secret'      => $this->secret,
            'layout'      => $this->layout,
        ];
    }

}