<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;

$this->layout('Webstatt::layouts/acp', ['title' => 'Dein Profil']);

/** @var ConfigService $configService */
$configService = $this->getConfig();
/** @var UserAbstraction $User */
$User = $this->getUser();
?>

<h1><i class="bi bi-person-circle"></i> <?= \basteyy\VariousPhpSnippets\__('Your profil') ?></h1>


<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <div class="row mb-3">
        <label for="_alias" class="col-sm-2 col-form-label"><?= \basteyy\VariousPhpSnippets\__('Your username') ?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="_alias" name="alias" autocomplete="disabled" required value="<?= $User->getAlias() ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="_name" class="col-sm-2 col-form-label"><?= \basteyy\VariousPhpSnippets\__('Your name') ?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="_name" name="name" autocomplete="disabled" required value="<?= $User->getName() ?>">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <?= \basteyy\VariousPhpSnippets\__('Save') ?>
    </button>
</form>

