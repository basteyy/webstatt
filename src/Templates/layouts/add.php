<?php

use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var PageEntity $page */

$this->layout('Webstatt::acp', ['title' => __('Add a new layout')]);
?>

<p class="">
    <a class="btn btn-primary" href="<?= $this->getAbsoluteUrl('/admin/layouts') ?>"><i class="bi bi-back"></i> <?= __('Back') ?></a>
</p>

<h1 class="my-md-5">
    <?= __('Add a new layout') ?>
</h1>


<form class="row g-5" method="post" action="<?= $this->getCurrentUrl() ?>">

    <div>
        <label for="name" class="form-label"><?= __('Name') ?></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="<?= __('A name for internal usage') ?>" required>
    </div>

    <div>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="activated" name="activated" value="yes">
            <label class="form-check-label" for="activated"><?= __('Allow users to select this layout') ?></label>
        </div>
    </div>

    <div>
        <button type="submit" class="btn btn-primary"><?= __('Add') ?></button>
    </div>
</form>
