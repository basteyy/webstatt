<?php

use basteyy\Webstatt\Models\Entities\SnippetEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var SnippetEntity $snippet */

$this->layout('Webstatt::acp', ['title' => __('Add a new snippet')]);
?>

<h1 class="my-md-5"><?= __('Add a new snippet') ?></h1>

<form class="row g-5" method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row mb-3">
        <label for="_name" class="col-sm-2 col-form-label"><?= __('Name/Description (intern)') ?></label>
        <input type="text" class="form-control" id="_name" name="name" placeholder="<?= __('Name/Description (intern)') ?>" required>
    </div>

    <div class="col-12">
        <label for="_key" class="form-label"><?= __('Key') ?></label>
        <input pattern="[A-z]{4,32}" type="text" class="form-control" id="_key" name="key" placeholder="<?= __('Key') ?>" required>
    </div>

    <div class="col-12">
        <label for="_content" class="form-label"><?= __('Content') ?></label>
        <textarea rows="10" class="form-control" id="_content" name="content" required placeholder="<?= __('Content (in HTML/PHP)')?>"></textarea>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="active" name="active">
            <label class="form-check-label" for="active"><?= __('Snippet activated') ?></label>
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="caching" name="caching">
            <label class="form-check-label" for="caching"><?= __('Caching enabled') ?></label>
        </div>
    </div>

    <div class="col-12">

        <input class="btn btn-primary" type="submit" value="<?= __('Add') ?>" />
    </div>
</form>