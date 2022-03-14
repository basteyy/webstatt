<?php

use basteyy\Webstatt\Enums\PageType;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit a content page')]);

/** @var PageType $page */
?>

<h1 class="my-md-5"><?= __('View content page version') ?></h1>


<p class="text-xs mt-2 alert alert-danger">
    <?= __('You are viewing a version of the content page.') ?>
</p>

<textarea class="form-control" style="min-height: 60vh;min-width:80vw;max-width: 99vw;"><?= $version_body ?></textarea>


<a class="mt-4 btn btn-secondary btn-sm" id="_preview_version" href="#" onclick="window.close()"><?= __('Close') ?></a>