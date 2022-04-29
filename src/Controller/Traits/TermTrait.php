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
use basteyy\Webstatt\Models\Entities\SnippetEntity;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

trait TermTrait {

    use ConfigTrait;

    protected function getTermContent() : string {

        if(!file_exists($this->getTermPath())) {
            $this->setTermContent(file_get_contents(SRC . 'Resources/StaticFiles/TERMS.md'));
        }

        return file_get_contents($this->getTermPath()) ?? '';
    }

    protected function setTermContent(string $content) : void {

        if(!is_dir(dirname($this->getTermPath()))) {
            mkdir(dirname($this->getTermPath()), 0755, true);
        }

        file_put_contents($this->getTermPath(), $content);
    }

    protected function getParsedTermContent() : string {
        return (new \Parsedown())->parse($this->getTermContent());
    }

    private function getTermPath() : string {
        return ROOT . DS . $this->configService->database_folder . DIRECTORY_SEPARATOR . 'term' . DIRECTORY_SEPARATOR . 'term.md';
    }

}
