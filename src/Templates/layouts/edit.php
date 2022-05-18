<?php

use basteyy\Webstatt\Models\Entities\LayoutEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var LayoutEntity $layout */

$this->layout('Webstatt::acp', ['title' => __('Edit layout %s', $layout->getName())]);
?>

<p class="">
    <a class="btn btn-primary" href="<?= $this->getAbsoluteUrl('/admin/layouts') ?>"><i class="bi bi-back"></i> <?= __('Back') ?></a>
</p>

<h1 class="my-md-5">
    <?= __('Edit layout %s', $layout->getName()) ?>
</h1>


<form class="row g-5" method="post" action="<?= $this->getCurrentUrl() ?>">

    <div>
        <label for="name" class="form-label"><?= __('Name') ?></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="<?= __('A name for internal usage') ?>" value="<?= $layout->getName() ?>">
    </div>

    <div>
        <button type="submit" class="btn btn-primary"><?= __('Save') ?></button>
    </div>
</form>
