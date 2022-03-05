<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Services;

use ReflectionClass;
use ReflectionException;

final class ConfigService
{
    /**
     * @var string $database_primary_key
     * @see config.ini
     */
    readonly string $database_primary_key;

    readonly string $database_pages_name;

    readonly string $database_users_name;

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



    /**
     * @throws ReflectionException
     */
    public function __construct(array $config)
    {
        $reflection = new ReflectionClass($this);

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
     * Magic getter
     * @param string $name
     * @return null|mixed
     */
    public function __get(string $name)
    {
        return $this->{$name} ?? null;
    }
}