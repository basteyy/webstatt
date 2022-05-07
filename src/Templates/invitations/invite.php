<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Models\Entities\InvitationEntity;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getNiceDateTimeFormat;

$this->layout('Webstatt::acp', ['title' => __('Manage invitations')]);
?>

<span class="float-end">
    <a href="<?= $this->getAbsoluteUrl('/admin/users') ?>" class="btn btn-primary btn-sm">
        <i class="bi-icon bi-back"></i> <?= __('Back zu managing users') ?>
    </a>
</span>

<h1 class="my-md-5"><?= __('Manage invitations') ?></h1>

<p>
    With an invitation link user can create a account by themselves. You can define a few validation-rules, which domain should be supported.
</p>

<div class="row">
    <div class="col-lg-6">

        <span class="float-end">
            <a href="" class="btn btn-primary btn-sm">
                <i class="bi-icon bi-plus-square"></i> <?= __('New direct invitation') ?>
            </a>
        </span>

        <h2>Pending direct invitations</h2>

        <p>
            There are no pending invitations yet.
        </p>

        <hr/>
        <p>
            <small>A direct invitation was sent to a specific e-Mailadresse. The invitation can only be accepted by using the link and the specific mail-address</small>
        </p>

    </div>

    <div class="col-lg-6">

        <span class="float-end">
            <a href="<?= $this->getAbsoluteUrl('/admin/users/invite/create_link') ?>" class="btn btn-primary btn-sm">
                <i class="bi-icon bi-plus-square"></i> <?= __('New invitation link') ?>
            </a>
        </span>

        <h2>Active invitation-links</h2>


        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th><?= __('ID') ?></th>
                    <th><?= __('Status') ?></th>
                    <th><?= __('User Role') ?></th>
                    <th><?= __('Usages left') ?></th>
                    <th><?= __('Valid until') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php

                if (!isset($pending_invitation_links) || !is_array($pending_invitation_links) || count($pending_invitation_links) < 1) {
                    printf('<tr><td colspan="4">%s</td></tr>', __('There are no pending invitations yet.'));
                }

                /** @var InvitationEntity $invitation_link */
                if (isset($pending_invitation_links) && is_array($pending_invitation_links)) {

                    foreach ($pending_invitation_links as $invitation_link) {

                        ?>
                        <tr>
                            <td rowspan="3"><?= $invitation_link->getId() ?></td>
                            <td><?= $invitation_link->getActiveStateBadge() ?></td>
                            <td><?= $invitation_link->getRole()->value ?></td>
                            <td><?= $invitation_link->getAcceptanceLimitLeft() ?></td>
                            <td><?= getNiceDateTimeFormat($invitation_link->getAcceptanceTimeoutDatetime()) ?></td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <code class="small"><pre><?= $this->getAbsoluteUrl($invitation_link->getInvitationLink()) ?><hr/><?= $invitation_link->getAcceptanceRules()
                                        ?></pre>
                                </code>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div class="btn-group">
                                    <a href="<?= $this->getAbsoluteUrl('/admin/users/invite/edit/' . $invitation_link->getSecretKey()) ?>" class="btn btn-primary btn-sm"><i
                                                class="bi bi-gear"></i> <?= __('Edit') ?></a>
                                    <a
                                            data-confirm="<?= __('Do you want to delete the selected invitation?') ?>"
                                            href="<?= $this->getAbsoluteUrl('/admin/users/invite/delete/' . $invitation_link->getSecretKey()) ?>"
                                            class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> <?= __('Delete') ?></a>
                                    <a href="#" data-clipboard-text="<?= $this->getAbsoluteUrl($invitation_link->getInvitationLink()) ?>" class="btn btn-primary btn-sm copy"><i
                                                class="bi bi-clipboard"></i> <?= __('Copy') ?></a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }

                ?>
            </table>
        </div>
    </div>
</div>