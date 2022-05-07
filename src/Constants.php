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

namespace basteyy\Webstatt;

use function basteyy\VariousPhpSnippets\__;

if(!isset($this->configService)) {
    throw new \Error(__('Do not access this file %s directly', __FILE__));
}

/** Put trash here */
define('TEMP', ROOT . DS . 'cache' . DS);

/** Base Public Folder in Filesystem */
define('PUB', ROOT . DS . 'public' . DS);

/** APCu installed and enabled to use it */
if (!defined('APCU_SUPPORT')) {
    define('APCU_SUPPORT', !$this->configService->caching_apcu_disabled && function_exists('apcu_enabled') && apcu_enabled());
}

/**APCu TTL */
if (APCU_SUPPORT) {
    define('APCU_TTL_LONG', $this->configService->caching_apcu_ttl_long ?? 720);
    define('APCU_TTL_MEDIUM', $this->configService->caching_apcu_ttl_medium ?? 60);
    define('APCU_TTL_SHORT', $this->configService->caching_apcu_ttl_short ?? 10);
}

/** @ar string W_PAGE_STORAGE_PATH Path where the content/the versions of pages are stored */
define('W_PAGE_STORAGE_PATH', ROOT . rtrim($this->configService->pages_private_folder, '/') . DS);

define('W_PAGES_ROUTES_CACHE_KEY', 'pages_routing_cache');
define('W_PAGES_STARTPAGE_CACHE_KEY', 'pages_startpage');

define('WEBSTATT_DEFAULT_FOLDER_PERMISSIONS', 0755);
define('WEBSTATT_CREATE_FOLDER_RECURSIVE', true);