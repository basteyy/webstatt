<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Models\Entities\UserEntity;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Add a new user')]);

/** @var UserEntity $user */
?>

<h1 class="my-md-5"><?= __('Edit user %s', $user->getAnyName()) ?></h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>">

    <div class="row mb-3">
        <label for="_email" class="col-sm-2 col-form-label"><?= __('E-Mail address') ?></label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="_email" name="email" value="<?= $user->getEmail() ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="_role" class="col-sm-2 col-form-label"><?= __('User-role') ?></label>
        <div class="col-sm-10">
            <select id="_role" class="form-select form-select-sm" aria-label="<?= __('Select a user-role') ?>" name="role">
                <optgroup label="<?= __('Select a user-role') ?>">
                    <option value="<?= UserRole::SUPER_ADMIN->value ?>"<?= $user->getRole() === UserRole::SUPER_ADMIN ? ' selected' : '' ?>>
                        <?= UserRole::SUPER_ADMIN->getTitle() ?>
                    </option>

                    <option value="<?= UserRole::ADMIN->value ?>"<?= $user->getRole() === UserRole::ADMIN ? ' selected' : '' ?>>
                        <?= UserRole::ADMIN->getTitle() ?>
                    </option>

                    <option value="<?= UserRole::USER->value ?>"<?= $user->getRole() === UserRole::USER ? ' selected' : '' ?>>
                        <?= UserRole::USER->getTitle() ?>
                    </option>

                </optgroup>
            </select>
        </div>
    </div>

    <hr>

    <div class="row mb-3">
        <label for="_alias" class="col-sm-2 col-form-label"><?= __('Username') ?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="_alias" name="alias" autocomplete="disabled" value="<?= $user->getAlias() ?>">
        </div>
    </div>

    <div class="row mb-3">
        <label for="_name" class="col-sm-2 col-form-label"><?= __('Name') ?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="_name" name="name" autocomplete="disabled" value="<?= $user->getName() ?>">
        </div>
    </div>


    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

