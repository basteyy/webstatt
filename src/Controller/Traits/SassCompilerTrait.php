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

use basteyy\ScssPhpBuilder\ScssPhpBuilder;
use Exception;

/**
 * Trait for Rendering-Jobs inside the controllers
 */
trait SassCompilerTrait {

    /**
     * Render Sass File
     * @return void
     * @throws Exception
     */
    protected function sassRenderStrategy(): void
    {
        $sass_starting_file = PUB . 'sass' . DS . 'style.scss';
        $css_final_file = PUB . 'css' . DS . 'style.css';

        if (file_exists($sass_starting_file)) {

            $compile = true;

            if (file_exists(PUB . 'css' . DS . 'style.css')) {
                if (filemtime($css_final_file) >= filemtime($sass_starting_file)) {
                    $compile = false;
                }
            }


            if ($compile) {
                $scss = new ScssPhpBuilder();
                $scss->addFolder(PUB . 'sass' . DS);
                $scss->addOutputeFile($css_final_file);
                $scss->addStartingFile($sass_starting_file);
                $scss->compileToOutputfile();
            }

        }

        // Do nothing
    }
}