<?php
declare(strict_types=1);

use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

error_reporting(E_ALL);

/** @var PageEntity $page */

if ($page->hasLayout() && 'no_design' !== $page->getLayout()) {
    $this->layout($page->getLayout(), ['page' => $page]);
}


if (PageType::HTML_PHP === $page->pageType) {

    if (!file_exists($page->getStorage()->getAbsoluteFilePath())) {
        throw new \Exception(__('Cant include %s', $page->getStorage()->getAbsoluteFilePath()));
    }

    include $page->getStorage()->getAbsoluteFilePath();

} elseif (PageType::MARKDOWN === $page->getPageType()) {
    echo $page->getMarkdownParsedBody(false);
} else {
    echo $page->getStorage()->getBody();
}

