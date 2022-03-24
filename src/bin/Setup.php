<?php

declare(strict_types=1);

namespace basteyy\Webstatt\bin;


use DirectoryIterator;
use SplFileInfo;
use function basteyy\VariousPhpSnippets\varDebug;

class Setup
{
    private static string $root;
    private static string $image_folder = '/Resources/Assets/img';
    private static string $css_folder = '/Resources/Assets/css';
    private static string $js_folder = '/Resources/Assets/js';

    private static string $app_public_folder;
    private static string $app_public_img_sub_folder = 'assets/img/';
    private static string $app_public_css_sub_folder = 'assets/css/';
    private static string $app_public_js_sub_folder = 'assets/js/';

    public static function run($event): void
    {

        self::$root = dirname(__DIR__);
        self::$app_public_folder = dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;

        // Move images
        self::copyImages();

        // Move Css
        self::copyCss();

        // Move js:
        self::copyJs();
    }


    /**
     * Copy the images
     * @return void
     */
    protected static function copyImages()
    {
        $source = self::clean_path(self::$root . DIRECTORY_SEPARATOR . self::$image_folder);
        $target = self::clean_path(self::$app_public_folder . DIRECTORY_SEPARATOR . self::$app_public_img_sub_folder);


        if ($target && $source) {
            self::copy_asset(
                $source,
                $target,
                ['png', 'jpg', 'jpeg', 'gif']
            );
        }
    }

    /**
     * Copy files from source to target.
     * @param string $source_path
     * @param string $target_path
     * @param array $file_pattern
     * @return void
     */
    private static function copy_asset(string $source_path, string $target_path, array $file_pattern)
    {

        if (is_dir($source_path)) {

            if (!is_dir($target_path)) {
                mkdir($target_path, 0755, true);
            }

            $folder_stream = new DirectoryIterator($source_path);

            /** @var SplFileInfo $file */
            foreach ($folder_stream as $file) {
                if ($file->isFile() && in_array(strtolower($file->getExtension()), $file_pattern)) {
                    if (!file_exists($target_path . DIRECTORY_SEPARATOR . $file->getBasename())) {
                        copy($file->getRealPath(), $target_path . DIRECTORY_SEPARATOR . $file->getBasename());
                        printf('Copy %s to %s', $file->getBasename(), $target_path);
                        print PHP_EOL;
                    }
                }
            }

        }
    }

    protected static function copyCss()
    {
        $source = self::clean_path(self::$root . DIRECTORY_SEPARATOR . self::$css_folder);
        $target = self::clean_path(self::$app_public_folder . DIRECTORY_SEPARATOR . self::$app_public_css_sub_folder);

        if ($target && $source) {
            self::copy_asset(
                $source,
                $target,
                ['css']
            );
        }

    }

    protected static function copyJs()
    {
        $source = self::clean_path(self::$root . DIRECTORY_SEPARATOR . self::$js_folder);
        $target = self::clean_path(self::$app_public_folder . DIRECTORY_SEPARATOR . self::$app_public_js_sub_folder);

        if ($target && $source) {
            self::copy_asset(
                $source,
                $target,
                ['js']
            );
        }
    }

    private static function clean_path(string $path) : string {
        return str_replace('//', '/', $path);
    }
}