<?php
/** @var PageAbstraction $page */

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;

if($page->hasLayout()) {
    $this->layout($page->getLayout(), ['page' => $page]);
}

if(\basteyy\Webstatt\Enums\ContentType::HTML_PHP === $page->getContentType() ) {

    if(!file_exists($page->getAbsoluteFilePath())) {
        throw new \Exception(\basteyy\VariousPhpSnippets\__('Cant include %s', $page->getAbsoluteFilePath()));
    }

    include $page->getAbsoluteFilePath();

} elseif ( \basteyy\Webstatt\Enums\ContentType::MARKDOWN === $page->getContentType() ) {
    echo $page->getParsedBody();
} else {
    echo $page->getBody();
}

