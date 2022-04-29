<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit the terms')]);


?>

<h1 class="my-md-5">
    <?= __('Edit the terms') ?>
</h1>

<form method="post">

    <div class="mb-3">

        <label for="terms" class="form-label">
            <span class="h2"><?= __('Terms') ?></span> <br />
            <span class="m-1 d-block">Use markdown to write the terms.</span>
        </label>

        <textarea id="terms" name="terms" class="w-100 form-control" rows="8"><?= $term ?></textarea>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary"><?= __('Save') ?></button>
        <a href="<?= $this->getAbsoluteUrl('/admin/terms') ?>" target="_blank" type="<?= __('View the terms') ?>">Show terms</a>
    </div>

</form>

<link rel="stylesheet" href="/assets/css/easymde.min.css">
<script src="/assets/js/easymde.min.js"></script>
<script>
    const easyMDE = new EasyMDE({element: document.getElementById("terms"), spellChecker: false});
</script>