<?php
/**
 * Webstatt
 *
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://webstatt.org
 * @website https://github.com/basteyy/webstatt
 * @license CC BY-SA 4.0
 */

/** @var $app App */
declare(strict_types=1);

use Slim\App;

if (!isset($this->app)) {
    throw new Exception(sprintf('Not allowed to access %s', __FILE__));
}
