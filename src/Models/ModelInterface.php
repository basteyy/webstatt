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

use basteyy\Webstatt\Models\Entities\EntityInterface;
use SleekDB\Store;

interface ModelInterface {

    /**
     * Create a new Entity
     * @param array $data
     * @return EntityInterface
     */
    public function create(array $data) : EntityInterface;

    /**
     * Update/Patch an entity with new data
     * @param EntityInterface $entity
     * @param array $data
     * @return EntityInterface
     */
    public function patch(EntityInterface $entity, array $data) : void;

    /**
     * Delete an entity
     * @param EntityInterface $entity
     * @return void
     */
    public function delete(EntityInterface $entity) : void;

    /**
     * Save an entity to database
     * @param EntityInterface $entity
     * @return mixed
     */
    public function save(EntityInterface $entity);

    /**
     * Return the raw Store-Class from SleekDb
     * @return Store
     */
    public function getRaw() : Store;
}