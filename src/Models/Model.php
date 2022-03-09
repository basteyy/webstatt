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

use basteyy\Webstatt\Models\Entities\Entity;
use basteyy\Webstatt\Models\Entities\EntityInterface;
use basteyy\Webstatt\Services\ConfigService;
use Exception;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
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

    public function patch(EntityInterface $entity, array $data): void
    {
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
    }

    public function save(EntityInterface $entity)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function findById(int $id): EntityInterface
    {
        return $this->create($this->getRaw()->findById($id));
    }

    public function create(array $data): EntityInterface
    {
        /* New element, when there is no primary id */
        if (!isset($data[$this->configService->database_primary_key])) {
            $data['__new'] = true;
        }

        $entity_class_name = $this->getEntityName();

        return new $entity_class_name($data, $this->configService->database_primary_key);
    }

    /**
     * Build the entity name for current table
     * @return string
     */
    private function getEntityName(): string
    {
        $called_class = get_called_class();

        if (APCU_SUPPORT && apcu_exists($called_class)) {
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

        /* Plural? */
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

                /* Cache the result */
                if (APCU_SUPPORT && apcu_exists($called_class)) {
                    apcu_add($called_class, $class, APCU_TTL_SHORT);
                }

                return $class;
            }
        }

        /** Default Entity Class */
        return Entity::class;
    }


    protected function getPrimaryIdName() : string {
        return $this->configService->database_primary_key;
    }

}