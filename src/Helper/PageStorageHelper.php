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

namespace basteyy\Webstatt\Helper;

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Models\Entities\PageEntity;
use DirectoryIterator;
use Exception;
use JetBrains\PhpStorm\Pure;
use SplFileInfo;
use function basteyy\VariousPhpSnippets\__;

final class PageStorageHelper {

    /** @var PageEntity  */
    private PageEntity $pageEntity;

    /** @var string $backup_folder_name Name of the folder for the versions */
    private string $backup_folder_name = '.versions' . DIRECTORY_SEPARATOR;

    /** @var string $file_name File name of the page on the file system */
    private string $file_name = 'content';

    /** @var string $file_markdown_extension File extension for markdown files */
    private string $file_markdown_extension = 'md';

    /** @var string $file_html_php_extension File extension for php files */
    private string $file_html_php_extension = 'php';

    /** @var int $maximum_versions Define, how many versions are saved */
    private int $maximum_versions;

    /** @var string $storage_folder_name Name of the folder for the page */
    private string $storage_folder_name;

    public function __construct(PageEntity $pageEntity, int $maximum_versions = 4)
    {
        $this->pageEntity = $pageEntity;
        $this->maximum_versions = $maximum_versions;
        $this->storage_folder_name = (string) $pageEntity->getId();
    }

    /**
     * Change the extension of all versions and the major file to the new page type
     * @throws Exception
     */
    public function changePageType(PageType $new_page_type) : void {

        /* Target Page Type File Extension */
        $current_extension = $this->getFileExtension();
        $new_extension = '.' . match ($new_page_type) {
            PageType::HTML_PHP => $this->file_html_php_extension,
            PageType::MARKDOWN => $this->file_markdown_extension
        };
        $current_major_version = $this->getAbsoluteFilePath();

        if($current_extension === $new_extension ) {
            throw new Exception(__('Page Type is already %s', $new_extension));
        }

        /* Rename all Versions */
        foreach($this->getAllVersions() as $filemtime => $file_path) {

            /* Copy to new file */
            copy($file_path, str_replace($current_extension, $new_extension, $file_path));

            /* Delete old file */
            unlink($file_path);
        }

        /* Rename current version */
        copy($current_major_version, str_replace($current_extension, $new_extension, $this->getAbsoluteFilePath()) );

        /* Delete old current version */
        unlink($current_major_version);
    }

    /**
     * Return the current file content
     * @param bool $use_cache
     * @return string
     */
    public function getBody(bool $use_cache = true) : string {

        if(APCU_SUPPORT && $use_cache && apcu_exists($this->pageEntity->cache_key_name)) {
            return apcu_fetch($this->pageEntity->cache_key_name);
        }

        $body = $this->loadFromFile();

        if($use_cache && APCU_SUPPORT) {
            apcu_add($this->pageEntity->cache_key_name, $body, APCU_TTL_LONG);
        }

        return $body;
    }

    /**
     * Load the content from the file. Return an empty string, if the file not exists.
     * @see file_get_contents()
     * @see file_exists()
     * @return string
     */
    #[Pure] private function loadFromFile(): string
    {
        return file_exists($this->getAbsoluteFilePath()) ? file_get_contents($this->getAbsoluteFilePath()) : '';
    }

    /**
     * Return the absolute file path of the file
     * @return string
     */
    #[Pure] private function getAbsoluteFilePath(): string
    {
        return $this->getFolderRealPath() . $this->file_name . $this->getFileExtension();
    }

    /**
     * Return the current folder real path
     * @return string
     */
    private function getFolderRealPath() : string {

        if (!is_dir(W_PAGE_STORAGE_PATH . $this->storage_folder_name)) {
            mkdir(W_PAGE_STORAGE_PATH . $this->storage_folder_name, 0755, true);
        }

        return W_PAGE_STORAGE_PATH . $this->storage_folder_name . DS;
    }

    /**
     * Get the file extension, which should be used
     * @param bool $leading_dot
     * @return string
     */
    #[Pure] private function getFileExtension(bool $leading_dot = true): string
    {
        return ($leading_dot ? '.' : '') . match ($this->pageEntity->getPageType()) {
                PageType::HTML_PHP => $this->file_html_php_extension,
                PageType::MARKDOWN => $this->file_markdown_extension
            };
    }

    /**
     * The Function will write `$body` as the new file content to the file
     * @param string $body
     * @param bool $make_backup
     * @return void
     */
    public function writeBody(string $body, bool $make_backup = true) : void {

        if($make_backup) {
            $this->makeBackup();
        }

        file_put_contents($this->getAbsoluteFilePath(), $body);
    }

    /**
     * Backup the current version
     * @return void
     */
    private function makeBackup() : void {

        $folder = $this->getFolderRealPath() . $this->backup_folder_name;
        $version_name = 'v_' . date('d_m_H_i_s') . $this->getFileExtension();

        $versions = $this->getAllVersions();

        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        if($this->maximum_versions !== -1 && $this->maximum_versions <= count($versions)+1) {
            /* Delete the oldest X versions */
            for($x=0; $x<= (($this->maximum_versions - (count($versions) +1)) * -1); $x++) {
                unlink($versions[key($versions)]);
                unseT($versions[key($versions)]);
            }
        }

        file_put_contents($folder . $version_name, $this->getBody(false));
    }

    /**
     * Returns an array with all versions of the file
     * @return array
     */
    public function getAllVersions() : array {

        if (!is_dir(W_PAGE_STORAGE_PATH . $this->storage_folder_name . DS . $this->backup_folder_name)) {
            return [];
        }

        $versions = [];
        $dir = new DirectoryIterator(W_PAGE_STORAGE_PATH . $this->storage_folder_name . DS . $this->backup_folder_name);

        foreach ($dir as $file) {
            /** @var SplFileInfo $file */

            /* Only accept files which the correct extension */
            if ($file->isFile() && $file->getExtension() === $this->getFileExtension(false)) {
                $versions[$file->getMTime()] = $file->getRealPath();
            }

        }

        ksort($versions);

        return $versions;
    }

    /**
     * Return current hash of the body
     * @return string
     */
    #[Pure] public function getHash() : string {
        return hash(FAST_HASH, $this->loadFromFile());
    }

    /**
     * Change the extension of the current mayor version and former version according to `$pageType`.
     * @param PageType $pageType
     * @return void
     */
    public function changeExtensionTo(PageType $pageType) : void {

        $new_extension = '.' . $pageType->fileExtension();

        /* Copy and delete the versions */
        foreach ($this->getAllVersions() as $timestamp => $path) {
            copy($path, str_replace($this->getFileExtension(true), $new_extension, $path));
            unlink($path);
        }

        /* Copy and delete the current */
        $old = $this->getAbsoluteFilePath();
        copy($this->getAbsoluteFilePath(), str_replace($this->getFileExtension(true), $new_extension, $this->getAbsoluteFilePath()));
        unlink($old);
    }


}