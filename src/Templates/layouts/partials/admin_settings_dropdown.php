<?php

use function basteyy\VariousPhpSnippets\__;

?>
<li class="nav-item dropdown mx-lg-3">
    <a class="nav-link dropdown-toggle" href="#" id="adminSettingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="mx-md-2 bi bi-gear mx-md-2"></i> <?= __('Settings') ?>
    </a>
    <ul class="dropdown-menu p-md-4" aria-labelledby="adminSettingsDropdown">

        <li>
            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/me') ?>" title="<?= __('Manage who\'s is allowed to access what admin-module') ?>">
                <i class="mx-md-2 bi bi-stoplights mx-md-2"></i> <?= __('Access Settings') ?>
            </a>
        </li>

        <li>
            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/settings/email') ?>" title="<?= __('Manage the mail settings') ?>">
                <i class="mx-md-2 bi bi-mailbox mx-md-2"></i> <?= __('E-Mail Settings') ?>
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/users/settings') ?>" title="<?= __('Manage the user settings') ?>">
                <i class="mx-md-2 bi bi-people"></i> <?= __('User Settings') ?>
            </a>
        </li>
        <li>
            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/users') ?>">
                <i class="mx-md-2 bi bi-people"></i> <?= __('User-management') ?>
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/cache/reset') ?>?return=<?=  $this->getCurrentUrl() ?>">
                <i class="mx-md-2 bi bi-calendar-check"></i> <?= __('Reset Cache') ?>
            </a>
        </li>

    </ul>
</li>