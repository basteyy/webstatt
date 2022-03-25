<?php

use function basteyy\VariousPhpSnippets\__;


$this->layout('Webstatt::layouts/acp', ['title' => __('Rename file')]);

/** @var SplFileInfo $file */

?>
<h1 class="my-md-5">
    <?= __('Rename file %s (%s)', $file->getBasename(), $file->getRealPath()) ?>
</h1>

<div class="my-3">
    <a class="btn btn-secondary" href="<?= $this->getAbsoluteUrl('/admin/files') ?>?folder=<?= base64_encode(dirname($file->getRealPath())) ?>"><?= __('Back to folder') ?></a>
</div>

<form method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row g-3">

        <div class="col-12">

            <label class="visually-hidden" for="file_name"><?= __('Filename') ?></label>
            <div class="input-group">
                <div class="input-group-text"><?= dirname($file->getRealPath()) . DS ?></div>
                <input type="text" class="form-control" name="file_name" id="file_name" placeholder="<?= __('Filename') ?>" value="<?= $file->getBasename() ?>">
            </div>
        </div>

    </div>

    <div class="text-end my-3">
        <input class="btn btn-primary" type="submit" value="<?= __('Rename') ?>"/>
    </div>
</form>