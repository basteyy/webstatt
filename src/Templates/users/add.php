<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Add a new user')]);
?>

<h1 class="my-md-5"><?= __('Add a new user') ?></h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row mb-3">
        <label for="_email" class="col-sm-2 col-form-label"><?= __('E-Mail address') ?></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="_email" name="email">
        </div>
    </div>

    <div class="row mb-3">
        <label for="_password" class="col-sm-2 col-form-label"><?= __('Password') ?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" id="_password" name="password">
        </div>
    </div>


    <div class="row mb-3">
        <label for="_role" class="col-sm-2 col-form-label"><?= __('User-role') ?></label>
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


    <button type="submit" class="btn btn-primary">
        <?= __('Add') ?>
    </button>
</form>

