<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('User Settings')]);
?>

<h1 class="my-md-5"><?= __('User Settings') ?></h1>

<p>
    <?= __('In the following you an change a few settings which define, how users can interact with your website.') ?>
</p>

<h2><?= __('Registration') ?></h2>

<form method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row m-2">
        <div class="form-check form-switch offset-md-2">
            <input class="form-check-input" type="checkbox" role="switch" id="_send_welcome_mail" name="send_welcome_mail" checked>
            <label class="form-check-label" for="_send_welcome_mail"><?= __('Activate self signup?') ?></label>
        </div>
    </div>

    <div class="row m-2">
        <div class="form-check form-switch offset-md-2">
            <input class="form-check-input" type="checkbox" role="switch" id="_send_welcome_mail" name="send_welcome_mail" checked>
            <label class="form-check-label" for="_send_welcome_mail"><?= __('Users needs to be accepted by a user') ?></label>
        </div>
    </div>

    <div class="row mb-3">
        <label for="_role" class="col-sm-2 col-form-label"><?= __('Minimum user-role for accepting new user') ?></label>
        <div class="col-sm-10">
            <select id="_role" class="form-select form-select-sm" aria-label=".form-select-sm example" name="role">
                <option selected><?= __('Select a user-role') ?></option>
                <optgroup label="Rolle auswählen">
                    <option value="<?= UserRole::SUPER_ADMIN->value ?>">
                        <?= UserRole::SUPER_ADMIN->getTitle() ?>
                    </option>

                    <option value="<?= UserRole::ADMIN->value ?>">
                        <?= UserRole::ADMIN->getTitle() ?>
                    </option>

                    <option value="<?= UserRole::USER->value ?>">
                        <?= UserRole::USER->getTitle() ?>
                    </option>

                </optgroup>
            </select>
        </div>
    </div>
</form>