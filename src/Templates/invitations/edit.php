<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\InvitationEntity $invitationEntity */

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit an invitation link')]);

?>

<span class="float-end">
    <a href="<?= $this->getAbsoluteUrl('/admin/users/invite') ?>" class="btn btn-primary btn-sm">
        <i class="bi-icon bi-back"></i> <?= __('Back zu managing invitations') ?>
    </a>
</span>

<h1 class="my-md-5"><?= __('Edit invitation link #%s', $invitationEntity->getId()) ?></h1>

<form method="post" class="mt-5" action="<?= $this->getCurrentUrl() ?>">

    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="link" class="col-form-label"><?= __('Link') ?></label>
        </div>

        <div class="col-sm-12 col-md-8">
            <input class="form-control w-100" value="<?= $this->getAbsoluteUrl($invitationEntity->getInvitationLink()) ?>" id="link" readonly />
        </div>
        <div class="col-md-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="yes" id="new_public_key" name="new_public_key">
                <label class="form-check-label badge bg-warning text-dark" for="new_public_key">
                    <?= __('Generate new url (old URL is not accessible after that)') ?>
                </label>
            </div>
        </div>
    </div>


    <div class="row g-3 mb-3">
        <div class="col-auto offset-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" <?= $invitationEntity->getActive() === true ? 'checked':'' ?>>
                <label class="form-check-label" for="active"><?= __('Link is active') ?></label>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="_role" class="col-sm-2 col-form-label"><?= __('User-role') ?></label>
        </div>
        <div class="col-auto">
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

    <div class="row g-3 mb-3">
        <div class="col-sm-2">
            <label for="acceptance_rules" class="col-form-label"><?= __('Validation Rules') ?></label>
        </div>

        <div class="col-auto">
            <textarea rows="5" class="form-control" id="acceptance_rules" name="acceptance_rules" placeholder="<?= __('Define validation rules or leave blank for no rules')
            ?>"><?= $invitationEntity->getAcceptanceRules() ?></textarea>

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
            <label for="acceptance_limit" class="col-form-label"><?= __('Maximum times of using') ?></label>
        </div>

        <div class="col-auto">

            <div class="row">
                <div class="col-auto"><input min="-1" type="number" class="form-control" id="acceptance_limit" name="acceptance_limit" autocomplete="disabled" value="<?=
                    $invitationEntity->getAcceptanceLimit()
                    ?>" /></div>
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
            <label for="used_times" class="col-form-label"><?= __('Already used times') ?></label>
        </div>

        <div class="col-auto">
            <div class="row">
                <div class="col-auto"><input readonly type="number" class="form-control" id="used_times" name="used_times" autocomplete="disabled" value="<?= $invitationEntity->getUsedTimes()
                    ?>" /></div>
                <div class="col-auto">
                    <a href="#_used"><?= __('See users which have used that link') ?></a>
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
                    <input type="date" class="form-control" id="acceptance_timeout_date" name="acceptance_timeout_date" autocomplete="disabled" value="<?=
                    $invitationEntity->getAcceptanceTimeoutDatetime()->format('Y-m-d') ?>" />
                </div>
                <div class="col-auto">
                    <input type="time" class="form-control" id="acceptance_timeout_time" name="acceptance_timeout_time" autocomplete="disabled" value="<?=
                    $invitationEntity->getAcceptanceTimeoutDatetime()->format('H:i') ?>" />
                </div>
                <div class="col-auto">
                    <span class="form-text"><?= __('Until when should the invitation be valid?') ?><br /><a target="_blank" class="fs-6" href="https://github
            .com/basteyy/webstatt/wiki/User-Managament#time-to-live"><?= __
                            ('See documentation') ?></a></span>
                </div>
            </div>
        </div>
    </div>


    <input class="btn btn-primary" value="<?= __('Save') ?>" type="submit" />

</form>

<hr />
<h2 id="_used"><?= __('Users which used this link') ?></h2>
<ul>
<?php
if(count($invitationEntity->getUsedUsers()) < 1 ) {
    printf('<li>%s</li>', __('No user has ever used that invitation link'));
} else {

    ?><div class="table-responsive">
    <table  class="table table-striped">

        <caption><?= __('List of users') ?></caption>
        <thead>
        <tr>
            <th scope="col"><?= __('#') ?></th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('Alias') ?></th>
            <th scope="col"><?= __('E-Mail') ?></th>
            <th scope="col"><?= __('User-role') ?></th>
            <th scope="col"><?= __('SignUp IP Address') ?></th>
            <th scope="col"><?= __('SignUp IP Date') ?></th>
            <th scope="col"><?= __('Last login') ?></th>
            <th scope="col"><?= __('Options') ?></th>
        </tr>
        </thead>
        <tbody>
    <?php

    /** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */
    foreach($invitationEntity->getUsedUsers() as $user) {
        ?>
    <tr>
        <td><?= $user->getId() ?></td>
        <td><?= $user->getAnyName() ?></td>
        <td><?= $user->getAlias() ?></td>
        <td><?= $user->getEmail() ?></td>
        <td><?= $user->getRoleBadge() ?></td>
        <td><?= $user->signupIp ?></td>
        <td><?= $user->getNiceCreatedDateTime() ?></td>
        <td><?= $user->getLastlogin() ?></td>
        <td>
            <div class="btn-group">
                <a class="btn btn-danger btn-sm" href="/admin/users/delete/<?= $user->getSecret() ?>?invitation=<?= $invitationEntity->getSecretKey() ?>" data-confirm="<?= __('Do you really want to delete user %s %s?',
                    $user->getAnyName(), $user->getEmail()) ?>"><i class="bi bi-trash"></i> <?= __('Delete') ?></a>
                <a class="btn btn-primary btn-sm" href="/admin/users/edit/<?= $user->getSecret() ?>"><i class="bi bi-gear"></i> <?= __('Edit') ?></a>
            </div>
        </td>

    </tr>
    <?php

    }
    ?>
        </tbody>
    </table></div>
    <?php
}


?></ul>