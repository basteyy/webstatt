<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Manage the users')]);
?>

<h1 class="my-md-5">Nutzer verwalten</h1>
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
                <td><?=
                    $user->getRole() === UserRole::SUPER_ADMIN ?
                        '<span class="badge rounded-pill bg-primary">Superadmin</span>' : (
                    $user->getRole() === UserRole::ADMIN ?
                        '<span class="badge rounded-pill bg-dark text-light">Admin</span>' :
                        '<span class="badge rounded-pill bg-light text-dark">User</span>')
                    ?></td>
                <td>
                    <a class="btn btn-danger btn-sm" href="/admin/users/delete/<?= $user->getSecret() ?>" data-confirm="<?= __('Do you really want to delete user %s %s?',
                $user->getAnyName(), $user->getEmail()) ?>"><?= __('Delete?') ?></a>
                    <a class="btn btn-primary btn-sm" href="/admin/users/edit/<?= $user->getSecret() ?>"><?= __('Edit') ?></a>
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
</p>