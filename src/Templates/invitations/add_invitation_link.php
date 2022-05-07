<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Invite a new user')]);
?>

<span class="float-end">
    <a href="<?= $this->getAbsoluteUrl('/admin/users/invite') ?>" class="btn btn-primary btn-sm">
        <i class="bi-icon bi-back"></i> <?= __('Back zu managing invitations') ?>
    </a>
</span>

<h1 class="my-md-5"><?= __('Create a new invitation link') ?></h1>

<form method="post" class="mt-5" action="<?= $this->getCurrentUrl() ?>">

    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="userRole" class="col-form-label"><?= __('User-role for new user') ?></label>
        </div>
        <div class="col-auto">
            <select id="userRole" class="form-select form-select-sm" name="userRole">
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


    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="acceptance_rules" class="col-form-label"><?= __('Validation Rules') ?></label>
        </div>

        <div class="col-auto">
            <textarea rows="5" class="form-control" id="acceptance_rules" name="acceptance_rules" placeholder="<?= __('Define validation rules or leave blank for no rules')
            ?>"></textarea>

            <span class="form-text">
                <?= __('You can limit to concrete addresses (<code>address@example.com</code>), specific domains (<code>*@example.com</code>) or TLD
                (<code>*.com</code>).') ?>
                <br />
                <a target="_blank" class="fs-6" href="https://github.com/basteyy/webstatt/wiki/User-Managament#validation-rules"><?= __('See documentation') ?></a>
            </span>
        </div>
    </div>


    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="acceptance_limit" class="col-form-label"><?= __('Maximum times uf using') ?></label>
        </div>

        <div class="col-auto">

            <div class="row">
                <div class="col-auto"><input min="-1" type="number" class="form-control" id="acceptance_limit" name="acceptance_limit" autocomplete="disabled" value="1" /></div>
                <div class="col-auto">
                    <span class="form-text">
                        <?= __('How many accounts can be created with the new invitation link? (-1 for no limit)') ?>
                        <br />
                        <a target="_blank" class="fs-6" href="https://github.com/basteyy/webstatt/wiki/User-Managament#using-limit"><?= __('See documentation') ?></a>
                    </span>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="acceptance_timeout_date" class="col-form-label"><?= __('Validate until date') ?></label>
            <label for="acceptance_timeout_time" class="col-form-label"><?= __('time') ?></label>
        </div>

        <div class="col-auto">

            <div class="row">
                <div class="col-auto">
                    <input type="date" class="form-control" id="acceptance_timeout_date" name="acceptance_timeout_date" autocomplete="disabled" value="<?= (new DateTime('+1 week'))
                        ->format('Y-m-d');?>" />
                </div>
                <div class="col-auto">
                    <input type="time" class="form-control" id="acceptance_timeout_time" name="acceptance_timeout_time" autocomplete="disabled" value="23:59" />
                </div>
                <div class="col-auto">
                    <span class="form-text"><?= __('Until when should the invitation be valid?') ?><br /><a target="_blank" class="fs-6" href="https://github
            .com/basteyy/webstatt/wiki/User-Managament#time-to-live"><?= __
                            ('See documentation') ?></a></span>
                </div>
            </div>
        </div>
    </div>


    <input class="btn btn-primary" value="<?= __('Create') ?>" type="submit" />

</form>