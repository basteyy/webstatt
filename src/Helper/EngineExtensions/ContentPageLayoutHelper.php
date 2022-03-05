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


namespace basteyy\Webstatt\Helper\EngineExtensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

final class ContentPageLayoutHelper implements ExtensionInterface
{
    private array $layouts;

    public function __construct(array $layouts)
    {
        $this->layouts = $layouts;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('getLayouts', fn () => $this->layouts);
    }
}