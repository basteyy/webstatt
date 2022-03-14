<?php declare(strict_types=1);

error_reporting(E_ALL);

/** @var \basteyy\Webstatt\Models\Entities\PageEntity $page */

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;

if($page->hasLayout()) {
    $this->layout($page->getLayout(), ['page' => $page]);
}


if(\basteyy\Webstatt\Enums\PageType::HTML_PHP === $page->pageType ) {

    if(!file_exists($page->getStorage()->getAbsoluteFilePath())) {
        throw new \Exception(\basteyy\VariousPhpSnippets\__('Cant include %s', $page->getStorage()->getAbsoluteFilePath()));
    }

    include $page->getStorage()->getAbsoluteFilePath();

} elseif ( \basteyy\Webstatt\Enums\PageType::MARKDOWN === $page->getPageType() ) {

    echo $page->getMarkdownParsedBody(false);
} else {
    echo $page->getStorage()->getBody();
}

