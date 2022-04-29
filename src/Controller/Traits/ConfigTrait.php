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

use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

trait ConfigTrait {

    private ConfigService $configService;

    protected function setConfigService(ConfigService $configService) {
        $this->configService = $configService;
    }

    protected function getConfigService() : ConfigService {
        return $this->configService;
    }
}