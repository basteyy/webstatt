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


namespace basteyy\Webstatt\Helper\EngineExtensions;

use basteyy\Webstatt\Models\Entities\UserEntity;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

final class GetUserEngineExtension implements ExtensionInterface  {

    private UserEntity|null $data;

    public function __construct(UserEntity|null $userData)
    {
        $this->data = $userData;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('getUser', fn() => $this->data);
    }

    public function __toString(): string
    {
        return (string) $this->data;
    }
}