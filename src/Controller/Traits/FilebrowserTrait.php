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

namespace basteyy\Webstatt\Controller\Traits;

trait FilebrowserTrait {

    /** @var string $base_folder base folder */
    protected string $base_folder = ROOT . DS . 'public' . DS;


    public function getBaseFolder() : string {
        return $this->base_folder;
    }
}