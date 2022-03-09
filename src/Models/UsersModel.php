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

namespace basteyy\Webstatt\Models;

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use basteyy\Webstatt\Models\Entities\UserEntity;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use function basteyy\VariousPhpSnippets\varDebug;

final class UsersModel extends Model
{

    protected string $database_name = 'users';

    protected int $count_all_users;


    /**
     * @throws ReflectionException
     */
    private function callEntity(array $data) : UserEntity {
        return new UserEntity($data, $this->getPrimaryIdName());
    }


    public function findBySecret(string $secret_key) : UserEntity {
        return $this->callEntity($this->getRaw()->findOneBy(['secret', '=', $secret_key]));
    }

    /**
     * Return the current numer of users inside the database
     * @param bool $force_new_counting
     * @return int
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     */
    public function getCountAll(bool $force_new_counting = false): int
    {
        if (!isset($this->count_all_users) || $force_new_counting) {
            $this->count_all_users = $this->getRaw()->count();
        }

        return $this->count_all_users;
    }

    /**
     * Return all users
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    public function getAll(): array
    {

        $users = $this->getRaw()->findAll();

        $this->count_all_users = count($users);
        $_users = [];
        foreach($users as $user ) {
            $_users[] = new UserEntity($user, $this->getPrimaryIdName());
        }

        return $_users;
    }
}