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
            <th scope="col"><?= __('E-Mail') ?></th>
            <th scope="col"><?= __('User-role') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($users as $user) {

            ?>

            <tr>
                <td><?= $user[$primary_id] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?=
                    $user['role'] === UserRole::SUPER_ADMIN->value ?
                        '<span class="badge rounded-pill bg-primary">Superadmin</span>' : (
                    $user['role'] === UserRole::ADMIN->value ?
                        '<span class="badge rounded-pill bg-dark text-light">Admin</span>' :
                        '<span class="badge rounded-pill bg-light text-dark">User</span>')
                    ?></td>
                <td><a href="/admin/users/delete/<?= $user['secret'] ?>" data-confirm="Nutzer wirklich löschen?"><?= __('Delete?') ?></a></td>
            </tr>

            <?php
        }

        ?>
        </tbody>
    </table>
</div>

<p>
    <a href="/admin/users/add"><?= __('Add a new user') ?></a>
</p>