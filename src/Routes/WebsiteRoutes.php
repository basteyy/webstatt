<?php
/** @var $app App */
declare(strict_types=1);

use Slim\App;

if (!isset($this->app)) {
    throw new Exception(sprintf('Not allowed to access %s', __FILE__));
}
