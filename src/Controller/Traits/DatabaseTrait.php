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

namespace basteyy\Webstatt\Controller\Traits;

use basteyy\Webstatt\Services\ConfigService;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Store;

/**
 * Trait provides the database methods
 */
trait DatabaseTrait {

    private ConfigService $configService;

    private array $_activeDatabases;

    /**
     * Return the database instance for the pages
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    protected function getContentPagesDatabase(): Store
    {
        return $this->getDatabase($this->configService->database_pages_name);
    }

    /**
     * Return the database instance for the users
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    protected function getUserDatabase(): Store
    {
        return $this->getDatabase($this->configService->database_users_name);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    private function getDatabase(string $database): Store
    {

        if (!isset($this->_activeDatabases) || !isset($this->_activeDatabases[$database])) {

            if (!is_dir(ROOT . DS . $this->configService->database_folder)) {
                mkdir(ROOT . DS . $this->configService->database_folder, 0755, true);
            }

            $this->_activeDatabases[$database] = new Store($database, ROOT . DS . $this->configService->database_folder, [
                'timeout'     => false,
                'primary_key' => $this->configService->database_primary_key
            ]);
        }

        return $this->_activeDatabases[$database];
    }
}