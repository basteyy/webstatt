<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Change your password')]);

/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $User */
$User = $this->getUser();
?>

<h1 class="my-md-5">
    <i class="mx-md-2 bi bi-envelope"></i> <?= __('Change your password') ?>
</h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <div class="row mb-3">
        <label for="_password" class="col-sm-2 col-form-label"><?= __('Current Password') ?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="_password" name="password" autocomplete="disabled" required>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <label for="_password_new" class="col-sm-2 col-form-label"><?= __('New password') ?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="_password_new" name="password_new" autocomplete="disabled" required>
        </div>
    </div>


    <div class="row mb-3">
        <label for="_password_new_confirm" class="col-sm-2 col-form-label"><?= __('Confirm the new password') ?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="_password_new_confirm" name="password_new_confirm" autocomplete="disabled" required>
        </div>
    </div>


    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

