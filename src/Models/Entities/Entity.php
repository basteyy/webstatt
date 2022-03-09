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

use ReflectionClass;
use ReflectionException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class Entity implements EntityInterface {

    private string $primary_id_name;

    /**
     * @throws ReflectionException
     */
    public function __construct(array $data, string $primary_id_name)
    {
        $this->primary_id_name = $primary_id_name;

        /* If the base entity is called, the property-checking is skipped */
        $skip_property_checking = __CLASS__ === get_called_class();

        /* Reflect current class to cast the properties */
        $reflection = new ReflectionClass($this);

        foreach ($data as $name => $value) {

            /* Only existing property's from the data will be patched to the class */
            if($name === $primary_id_name || $skip_property_checking || $reflection->hasProperty($name)) {

                /* Enum? */
                if($name === $primary_id_name || $reflection->getProperty($name)->getType()->isBuiltin()) {
                    $this->{$name} = $value;
                } else {
                    $enum = $reflection->getProperty($name)->getType()->getName();
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