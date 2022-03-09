<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => 'E-Mailadresse anpassen']);

/** @var ConfigService $configService */
$configService = $this->getConfig();
/** @var UserAbstraction $User */
$User = $this->getUser();
?>

<h1 class="my-md-5">
    <i class="mx-md-2 bi bi-envelope"></i> <?= __('Change your e-mail address') ?>
</h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <div class="row mb-3">
        <label for="_current_mail" class="col-sm-2 col-form-label"><?= __('E-Mail') ?></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="_current_mail" readonly value="<?= $User->getEmail() ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="email" class="col-sm-2 col-form-label"><?= __('Your <strong>new</strong> E-Mail') ?></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" autocomplete="disabled" required>
        </div>
    </div>


    <div class="row mb-3">
        <label for="_mail_confirm" class="col-sm-2 col-form-label"><?= __('Confirm your <strong>new</strong> E-Mail address') ?></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="_mail_confirm" name="email_confirm" autocomplete="disabled" required>
        </div>
    </div>
    <hr>

    <div class="row mb-3">
        <label for="_password" class="col-sm-2 col-form-label"><?= __('Password') ?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="_password" name="password" autocomplete="disabled" required>
        </div>
    </div>


    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

