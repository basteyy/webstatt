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

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Helper\PageStorageHelper;
use JetBrains\PhpStorm\Pure;
use function basteyy\VariousPhpSnippets\varDebug;

class SnippetEntity extends Entity implements EntityInterface
{
    protected string $name;
    protected string $key;
    protected string $content;
    protected string $secret;
    protected bool $active;
    protected bool $cache;



    public function isActive() : bool {
        return isset($this->active) && $this->active === true;
    }

    public function isCaching() : bool {
        return isset($this->cache) && $this->cache === true;
    }

    /**
     * @throws \Throwable
     */
    public function execute() {

        if(!$this->isActive()) {
            return '';
        }

        if($this->isCaching() && APCU_SUPPORT && apcu_exists('snippet_' . $this->getId() )) {
            return '<!-- Snippet['.$this->getKey().'] (from cache) -->' . apcu_fetch('snippet_' . $this->getId() ) . '<!-- /Snippet['.$this->getKey().'] -->';
        }

        // Search for file
        if(!file_exists($this->getCachedFileRealPath())) {

            //$content = (!str_starts_with($this->content, '<?php') && !str_starts_with($this->content, '<?=') ? '<?php ' : '') . $this->content;
            $content = $this->content;

            file_put_contents($this->getCachedFileRealPath(), $content);
        }



        try {
            $level = ob_get_level();
            ob_start();

            include $this->getCachedFileRealPath();

            $executed = ob_get_clean();

        } catch (\Throwable|\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        if(APCU_SUPPORT) {
            apcu_add('snippet_' . $this->getId() , $executed, APCU_TTL_MEDIUM);
        }

        return $executed;
    }

    public function getCachedFileRealPath() : string {

        if(!is_dir(TEMP . 'snippets' . DS)) {
            mkdir(TEMP . 'snippets' . DS, 0755, true);
        }

        return TEMP . 'snippets' . DS . $this->key . '.php';
    }

}