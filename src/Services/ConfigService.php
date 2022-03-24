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

namespace basteyy\Webstatt\Services;

use basteyy\Webstatt\Enums\ConfigFile;
use ReflectionClass;
use ReflectionException;
use function basteyy\VariousPhpSnippets\varDebug;

/**
 * Abstraction of the config.ini as a service
 */
final class ConfigService
{
    /** @var string $database_primary_key */
    readonly string $database_primary_key;

    /** @var string  */
    readonly string $database_folder;

    /** @var string $website Define the state of your website (production or development) */
    readonly string $website;

    /** @var bool $debug Debug state */
    readonly bool $debug;

    /** @var string $session_name Name of the session */
    readonly string $session_name;

    /** @var string $session_timeout How long before the session are timed out? */
    readonly string $session_timeout;

    /** @var bool $session_auto_refresh Should the session refresh automatically? */
    readonly bool $session_auto_refresh;

    /** @var string $agency_name Name your agency who takes care of the user of the website */
    readonly string $agency_name;

    /** @var string $agency_email Your support e-mail */
    readonly string $agency_email;

    /** @var string $agency_website Your support website */
    readonly string $agency_website;

    /** @var string $pages_private_folder Folder of content pages are stored */
    readonly string $pages_private_folder;

    /** @var int $pages_max_versions Number of supported versions of every content page */
    readonly int $pages_max_versions;

    /** @var bool $caching_apcu_disabled Disable/Enable APCu Caching */
    readonly bool $caching_apcu_disabled;

    /** @var int $caching_apcu_ttl The TTL of long stored APCu-Cache */
    readonly int $caching_apcu_ttl_long;


    /** @var int $caching_apcu_ttl The TTL of medium stored APCu-Cache */
    readonly int $caching_apcu_ttl_medium;

    /** @var int $caching_apcu_ttl The TTL of short stored APCu-Cache */
    readonly int $caching_apcu_ttl_short;

    /** @var string $website_url Basic URL of the project */
    readonly string $website_url;

    readonly string $config_folder;
    readonly string $config_mail_config_file_name;

    /**
     * @throws ReflectionException
     */
    public function __construct(array $config)
    {
        $reflection = new ReflectionClass($this);

        if(isset($config['website_url'])) {
            $config['website_url'] = $this->prepareBaseDomain($config['website_url']);
        }

        foreach ($config as $name => $value) {
            $this->{$name} = $reflection->hasProperty($name) !== null && $reflection->getProperty($name)->hasType() ? match ($reflection->getProperty($name)->getType()->getName
            ()) {
                'bool' => (boolean)$value,
                'int' => (int)$value,
                default => $value,
            } : $value;
        }

    }

    /**
     * Returns the project/current base URL.
     * @param string $url
     * @return string
     */
    private function prepareBaseDomain(string $url) : string {

        if('auto' === $url ) {
            // Generate the basic url
            return $this->prepareBaseDomain($_SERVER['SERVER_NAME']);
        }


        if(str_starts_with($url, 'http')) {
            return $url;
        }

        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http') . '://' . $url;
    }

    /**
     * Magic getter
     * @param string $name
     * @return null|mixed
     */
    public function __get(string $name)
    {
        return $this->{$name} ?? null;
    }

    /**
     * Return the Config Folder for the configs
     * @return string
     */
    public function getConfigFolder() : string {

        if(!is_dir(ROOT . DIRECTORY_SEPARATOR . $this->config_folder)) {
            mkdir(ROOT . DIRECTORY_SEPARATOR . $this->config_folder, 0755, true);
        }

        return ROOT . DIRECTORY_SEPARATOR . $this->config_folder . DIRECTORY_SEPARATOR;
    }

    /**
     * Return the Mail Config File Path
     * @return string
     */
    public function getMailConfigPath() : string {
        return $this->getConfigFolder() . $this->config_mail_config_file_name;
    }

    /**
     * Return the Mail Config
     * @return array
     */
    public function getMailConfig() : array {
        return $this->loadAdditionalConfigFile(ConfigFile::MAIL);
    }

    /**
     * Load a specific config file
     * @param ConfigFile $configFile
     * @return array
     */
    protected function loadAdditionalConfigFile(ConfigFile $configFile) : array {

        if(APCU_SUPPORT && apcu_exists($configFile->cacheName())) {
            return apcu_fetch($configFile->cacheName());
        }

        $config = parse_ini_file(match($configFile){
            ConfigFile::MAIL => $this->getMailConfigPath()
        }, false, INI_SCANNER_TYPED);

        if(APCU_SUPPORT) {
            apcu_add($configFile->cacheName(), $config, APCU_TTL_LONG);
        }

        return $config;
    }

}