<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Manage the users')]);
?>

<h1 class="my-md-5"><?= __('Manage the users') ?></h1>
<div class="table-responsive">
    <table class="table">
        <caption><?= __('List of users') ?></caption>
        <thead>
        <tr>
            <th scope="col"><?= __('#') ?></th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('Alias') ?></th>
            <th scope="col"><?= __('E-Mail') ?></th>
            <th scope="col"><?= __('User-role') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        /** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */
        foreach ($users as $user) {

            ?>

            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= $user->hasName() ? $user->getName() : '' ?></td>
                <td><?= $user->hasAlias() ? $user->getAlias() : '' ?></td>
                <td><?= $user->hasEmail() ? $user->getEmail() : '' ?></td>
                <td><?= $user->getRoleBadge() ?></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-danger btn-sm" href="/admin/users/delete/<?= $user->getSecret() ?>" data-confirm="<?= __('Do you really want to delete user %s %s?',
                $user->getAnyName(), $user->getEmail()) ?>"><i class="bi bi-trash"></i> <?= __('Delete') ?></a>
                    <a class="btn btn-primary btn-sm" href="/admin/users/edit/<?= $user->getSecret() ?>"><i class="bi bi-gear"></i> <?= __('Edit') ?></a>
                    </div>
                </td>
            </tr>

            <?php
        }

        ?>
        </tbody>
    </table>
</div>

<p class="text-end">
    <a href="/admin/users/add" class="btn btn-secondary"><?= __('Add a new user') ?></a>
    <a href="/admin/users/invite" class="btn btn-secondary"><?= __('Invitations') ?></a>
</p>