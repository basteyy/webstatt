<?php

use basteyy\Webstatt\Enums\UserRole;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Manage the users')]);
?>

<h1>Nutzer verwalten</h1>
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
        foreach ($users as $_user) {

            $user = new \basteyy\Webstatt\Models\Abstractions\UserAbstraction($_user, $this->getConfig());

            ?>

            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= $user->getName() ?></td>
                <td><?= $user->getAlias() ?></td>
                <td><?= $user->getEmail() ?></td>
                <td><?=
                    $user->getRole() === UserRole::SUPER_ADMIN ?
                        '<span class="badge rounded-pill bg-primary">Superadmin</span>' : (
                    $user->getRole() === UserRole::ADMIN ?
                        '<span class="badge rounded-pill bg-dark text-light">Admin</span>' :
                        '<span class="badge rounded-pill bg-light text-dark">User</span>')
                    ?></td>
                <td><a class="btn btn-danger btn-sm" href="/admin/users/delete/<?= $user->getSecret() ?>" data-confirm="Nutzer wirklich löschen?"><?= __('Delete?') ?></a></td>
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