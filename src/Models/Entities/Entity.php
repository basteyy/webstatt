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
    private array $accessible_properties;
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

            if($name === $primary_id_name) {
                // Thats the ID
                $this->{$primary_id_name} = $value;

            } elseif($skip_property_checking || $this->reflectionClass->hasProperty($name)) {
                /* Only existing property's from the data will be patched to the class */

                $this->accessible_properties[] = $name;

                /* Enum? */
                if($name !== $primary_id_name && $this->reflectionClass->getProperty($name)->getType()->isBuiltin()) {

                    $this->{$name} = match ($this->reflectionClass->getProperty($name)->getType()->getName()) {
                        'int' => (int)$value,
                        'bool' => (bool)$value,
                        default => $value,
                    };

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
            $var = lcfirst(substr($name, 3));

            if($this->_isAccessibleProperty($var)) {
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

        if($this->_isAccessibleProperty($name)) {
            return $this->{$name};
        }
    }

    /**
     * @throws ReflectionException
     */
    private function _isAccessibleProperty(string $property_name) : bool {
        return in_array($property_name, $this->accessible_properties) && $this->reflectionClass->hasProperty($property_name) && !$this->reflectionClass->getProperty
            ($property_name)->isPrivate();
    }

}