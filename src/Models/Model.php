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

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Models\Entities\Entity;
use basteyy\Webstatt\Models\Entities\EntityInterface;
use basteyy\Webstatt\Models\Entities\PageEntity;
use basteyy\Webstatt\Services\ConfigService;
use Exception;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use SleekDB\Store;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

/**
 * Basic Model
 */
class Model implements ModelInterface
{

    /** @var string $database_name The name of the database, which is used */
    protected string $database_name;

    /** @var ConfigService */
    private ConfigService $configService;

    private Store $store;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;

        if (!isset($this->database_name)) {
            throw new Exception(__('You need to specify the database name for the model.'));
        }
    }

    /**
     * @param EntityInterface $entity
     * @param array $data
     * @return void
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws JsonException
     * @throws Exception
     * @todo Implement strict types like __construct
     */
    public function patch(EntityInterface $entity, array $data): void
    {

        $reflection = new \ReflectionClass($entity);

        foreach($data as $name => $value) {
            if($reflection->hasProperty($name) && $reflection->getProperty($name)->hasType()) {

                /**Enum? */
                if($name !== $this->configService->database_primary_key && $reflection->getProperty($name)->getType()->isBuiltin()) {

                    $data[$name] = match ($reflection->getProperty($name)->getType()->getName()) {
                        'int' => (int)$value,
                        'bool' => (bool)$value,
                        default => $value,
                    };

                } else {

                    $enum = $reflection->getProperty($name)->getType()->getName();

                    if(is_string($value)) {
                        $data[$name] = $enum::tryFrom($value);
                    } elseif((new \ReflectionEnum($value))->getName() === $enum) {
                        $data[$name] = $value;
                    } else {
                        throw new \Exception(__('Invalid enum type %s cannot assigned to enum %s', (new \ReflectionEnum($value))->getName(), $enum));
                    }
                }
            }
        }

        $this->getRaw()->updateById($entity->getId(), $data);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    public function getRaw(): Store
    {
        if (!isset($this->store)) {

            if (!is_dir(ROOT . DS . $this->configService->database_folder)) {
                mkdir(ROOT . DS . $this->configService->database_folder, 0755, true);
            }

            $this->store = new Store($this->database_name, ROOT . DS . $this->configService->database_folder, [
                'timeout'     => false,
                'primary_key' => $this->configService->database_primary_key
            ]);
        }

        return $this->store;
    }

    public function delete(EntityInterface $entity): void
    {
        // TODO: Implement delete() method.
        $this->getRaw()->deleteById($entity->getId());
    }

    public function save(EntityInterface $entity)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findById(int $id, bool $use_cache = true): EntityInterface
    {
        return $this->createEntities($this->getRaw()->findById($id));
    }

    /**
     * Method turns array elements to the entities
     * @param array $entries
     * @return array|EntityInterface|null
     * @throws ReflectionException
     */
    protected function createEntities(array $entries): array|EntityInterface|null
    {
        if (count($entries) === 0) {
            return null;
        }

        $entity_class_name = $this->getEntityName();

        if (!isset($entries[0][$this->getPrimaryIdName()])) {

            /**New element, when there is no primary id */
            if (!isset($entries[$this->configService->database_primary_key])) {
                $entries['__new'] = true;
            }

            return new $entity_class_name($entries, $this->getPrimaryIdName());
        }

        $pages = [];
        foreach ($entries as $entry) {

            /**New element, when there is no primary id */
            if (!isset($entry[$this->configService->database_primary_key])) {
                $entry['__new'] = true;
            }

            $pages[] = new $entity_class_name($entry, $this->getPrimaryIdName());
        }
        return $pages;
    }

    /**
     * Build the entity name for current table
     * @param bool $use_cache
     * @return string
     */
    private function getEntityName(bool $use_cache = true): string
    {
        $called_class = get_called_class();

        if ($use_cache && APCU_SUPPORT && apcu_exists($called_class)) {
            return apcu_fetch($called_class);
        }

        $class_name_cases = [];
        $class_name = substr($called_class, (strlen(__NAMESPACE__) + 1));

        if (str_ends_with($class_name, 'Model')) {
            $class_name = substr($class_name, 0, -5);
        }

        $class_name_cases[] = $class_name;
        $class_name_cases[] = __NAMESPACE__ . '\\' . $class_name;
        $class_name_cases[] = __NAMESPACE__ . '\\' . $class_name . 'Entity';
        $class_name_cases[] = __NAMESPACE__ . '\\Entities\\' . $class_name;
        $class_name_cases[] = __NAMESPACE__ . '\\Entities\\' . $class_name . 'Entity';

        /**Plural? */
        if (str_ends_with($class_name, 's')) {
            $class_name = substr($class_name, 0, -1);
            $class_name_cases[] = $class_name;
            $class_name_cases[] = __NAMESPACE__ . '\\' . $class_name;
            $class_name_cases[] = __NAMESPACE__ . '\\' . $class_name . 'Entity';
            $class_name_cases[] = __NAMESPACE__ . '\\Entities\\' . $class_name;
            $class_name_cases[] = __NAMESPACE__ . '\\Entities\\' . $class_name . 'Entity';
        }

        foreach ($class_name_cases as $class) {
            if (class_exists($class)) {

                /**Cache the result */
                if ($use_cache && APCU_SUPPORT && apcu_exists($called_class)) {
                    apcu_add($called_class, $class, APCU_TTL_SHORT);
                }

                return $class;
            }
        }

        /** Default Entity Class */
        return Entity::class;
    }

    protected function getPrimaryIdName(): string
    {
        return $this->configService->database_primary_key;
    }

    /**
     * @inheritDoc
     * @param array $entity_data
     * @return EntityInterface
     * @throws ReflectionException
     */
    public function create(array $entity_data): EntityInterface
    {

        /**save to Sleek */
        $data = $this->getRaw()->insert($entity_data);

        return $this->createEntities($data);
    }

    protected function getDatabaseName(): string
    {
        return $this->database_name;
    }


    /**
     * @throws ReflectionException
     */
    protected function _findByOneArgument(
        string $search_field,
        string $operator,
        mixed  $search_value,
        bool   $multiple_results = false,
        bool   $create_entities = true,
        bool   $use_cache = true
    ): array|null|EntityInterface
    {
        $key = hash('xxh3', $search_field . $operator . $search_value);

        if ($use_cache && APCU_SUPPORT && apcu_exists($key)) {
            $data = apcu_fetch($key);
        } else {
            $data = $this->_findByArgumentsArray([$search_field, $operator, $search_value], $multiple_results);
        }

        if (!$data) {
            return null;
        }

        if (APCU_SUPPORT) {
            apcu_add($key, $data, APCU_TTL_SHORT);
        }

        return $create_entities ? $this->createEntities($data) : $data;
    }


    protected function _findByArgumentsArray(
        array $find_argument,
        bool   $multiple_results = false
    ): array|null
    {
        return $multiple_results ? $this->getRaw()->findBy($find_argument) : $this->getRaw()->findOneBy($find_argument);
    }



    /**
     * @throws IOException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException|ReflectionException
     */
    public function getAll(): array
    {
        $entries = $this->getRaw()->findAll();
        return 0 < count($entries) ? $this->createEntities($entries) : [];
    }
}