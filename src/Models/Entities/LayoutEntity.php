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

use basteyy\Webstatt\Helper\LayoutStorageHelper;

class LayoutEntity extends Entity implements EntityInterface
{
    /** @var string $name Name of the layout */
    protected string $name;

    protected string $secret;

    /** @var bool $activated True if the layout is ready to use */
    protected bool $activated;

    public function __construct(array $data, string $primary_id_name)
    {
        parent::__construct($data, $primary_id_name);
    }

    /**
     * Return the name of the layout
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return the state of activation of the layout
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * Get the Storage helper to get access to the file
     * @return LayoutStorageHelper
     */
    public function getStorage(): LayoutStorageHelper
    {
        if (!isset($this->layoutStorageHelper)) {
            $this->layoutStorageHelper = new LayoutStorageHelper($this);
        }

        return $this->layoutStorageHelper;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }
}
