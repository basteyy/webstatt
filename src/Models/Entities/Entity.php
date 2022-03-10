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

namespace basteyy\Webstatt\Models\Entities;

use Exception;
use ReflectionClass;
use ReflectionException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class Entity implements EntityInterface {

    private string $primary_id_name;
    private array $accessable_properties;
    private ReflectionClass $reflectionClass;

    /**
     * @throws ReflectionException
     */
    public function __construct(array $data, string $primary_id_name)
    {
        $this->primary_id_name = $primary_id_name;

        /* If the base entity is called, the property-checking is skipped */
        $skip_property_checking = __CLASS__ === get_called_class();

        /* Reflect current class to cast the properties */
        $this->reflectionClass = new ReflectionClass($this);

        foreach ($data as $name => $value) {

            /* Only existing property's from the data will be patched to the class */
            if($name === $primary_id_name || $skip_property_checking || $this->reflectionClass->hasProperty($name)) {

                $this->accessable_properties[] = $name;

                /* Enum? */
                if($name === $primary_id_name || $this->reflectionClass->getProperty($name)->getType()->isBuiltin()) {
                    $this->{$name} = $value;
                } else {
                    $enum = $this->reflectionClass->getProperty($name)->getType()->getName();
                    $this->{$name} = $enum::tryFrom($value);
                }
            }
        }
    }

    public function getId(): int
    {
        return $this->{$this->primary_id_name};
    }

    /**
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        if(substr($name, 0, 3) === 'get' ) {
            $var = strtolower(substr($name, 3));

            // Only public or protected properties are accessible by magic __call
            if(in_array($var, $this->accessable_properties) && $this->reflectionClass->hasProperty($var) && !$this->reflectionClass->getProperty($var)->isPrivate()) {
                return $this->{$var};
            }
        }

    }

    /**
     * @throws Exception
     */
    public function __get(string $name)
    {
        if (!isset($this->{$name})) {
            throw new Exception(__('Unknown property %s in %s', $name, get_called_class()));
        }

        return $this->{$name};
    }


}