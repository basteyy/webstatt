<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Manage invitations')]);
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

        <hr />
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

        <div class="card-group">
        <?php

        \basteyy\VariousPhpSnippets\varDebug($pending_invitation_links);

        if(!isset($pending_invitation_links) || !is_array($pending_invitation_links) || count($pending_invitation_links) < 1 ) {
            printf('<p>%s</p>', __('There are no pending invitations yet.'));
        }

        /** @var \basteyy\Webstatt\Models\Entities\InvitationEntity $invitation_link */
        if(isset($pending_invitation_links) && is_array($pending_invitation_links)) {

        foreach ($pending_invitation_links as $invitation_link) {

            ?>
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <p class="float-end fs-6">
                        <span class="badge bg-success rounded-pill float-end"><?= __('Active') ?></span>
                        <span class="badge bg-success rounded-pill float-end"><?= __('%s usingtimes left', $invitation_link->getAcceptanceLimitLeft()) ?></span>
                    </p>
                    <h5 class="card-title h-6">
                        <?= __('ID %s', $invitation_link->getId()) ?>
                    </h5>
                    <p class="card-text fs-6">
                        <?= __('Valid until: %s', \basteyy\VariousPhpSnippets\getNiceDateTimeFormat($invitation_link->getAcceptanceTimeoutDatetime())) ?>
                        <pre><code class="small"><?= $invitation_link->getAcceptanceRules() ?></code></pre>
                    </p>
                    <a href="#" class="btn btn-primary"><?= __('Edit') ?></a>
                </div>
            </div>
        <?php
        }
        }

        ?>
        </div>

        <hr />
        <p>
            <small>With an invitation-link you can invite more than one user.</small>
        </p>

    </div>
</div>