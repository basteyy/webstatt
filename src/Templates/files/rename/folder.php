<?php

use function basteyy\VariousPhpSnippets\__;


$this->layout('Webstatt::acp', ['title' => __('Rename folder')]);

/** @var SplFileInfo $file */

?>
<h1 class="my-md-5">
    <?= __('Rename Folder %s', $file->getBasename()) ?>
</h1>

<div class="my-3">
    <a class="btn btn-secondary" href="<?= $this->getAbsoluteUrl('/admin/files') ?>?folder=<?= base64_encode(dirname($file->getRealPath())) ?>"><?= __('Back to folder') ?></a>
</div>

<form method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row g-3">

        <div class="col-12">

            <label class="visually-hidden" for="folder_name"><?= __('Foldername') ?></label>
            <div class="input-group">
                <div class="input-group-text"><?= dirname($file->getRealPath()) . DS ?></div>
                <input type="text" class="form-control" name="folder_name" id="folder_name" placeholder="<?= __('Folder Name') ?>" value="<?= $file->getBasename() ?>">
            </div>
        </div>

    </div>

    <div class="text-end my-3">
        <input class="btn btn-primary" type="submit" value="<?= __('Rename') ?>"/>
    </div>
</form>