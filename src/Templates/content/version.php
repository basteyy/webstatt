<?php

use basteyy\Webstatt\Enums\ContentType;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit a content page')]);

/** @var \basteyy\Webstatt\Models\Abstractions\PageAbstraction $page */
?>

<h1><?= __('View content page version') ?></h1>


<p class="text-xs mt-2 alert alert-danger">
    <?= __('You are viewing a version of the content page.') ?>
</p>

<textarea class="form-control" style="min-height: 60vh;min-width:80vw;"><?= $version_body ?></textarea>


<a class="mt-4 btn btn-secondary btn-sm" id="_preview_version" href="#" onclick="window.close()"><?= __('Close') ?></a>