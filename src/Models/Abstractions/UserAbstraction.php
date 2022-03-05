<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Models\Abstractions;

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\varDebug;
use function DI\value;

final class UserAbstraction
{
    private string $email;
    private string $password;
    private string $salt;
    private UserRole $role;
    private string $secret;
    private string $name;
    private string $alias;
    private array $raw_user_data;
    private int $id;

    public function __construct(array $user_data, ConfigService $configService)
    {
        $this->raw_user_data = $user_data;

        $this->email        = $user_data['email'] ?? false;
        $this->password     = $user_data['password'] ?? false;
        $this->salt         = $user_data['salt'] ?? false;
        $this->role         = UserRole::from($user_data['role']) ?? UserRole::ANONYMOUS;
        $this->secret       = $user_data['secret'] ?? false;
        $this->name         = $user_data['name'] ?? '';
        $this->alias        = $user_data['alias'] ?? '';
        $this->id           = $user_data[$configService->database_primary_key];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function __toString(): string
    {
        return implode(',', $this->raw_user_data);
    }

    public function __get(string $name)
    {
        return $this->{$name} ?? false;
    }
}