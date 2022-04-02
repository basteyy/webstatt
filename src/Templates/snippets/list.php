<?php

use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\SnippetEntity $snippet */

$this->layout('Webstatt::layouts/acp', ['title' => __('Overview of your snippets')]);
?>

<h1 class="my-md-5"><?= __('Manage the snippets') ?></h1>

<div class="float-md-end">
    <a class="btn btn-primary" title="<?= __('Add a new snippet') ?>" href="<?= $this->getAbsoluteUrl('/admin/snippets/add') ?>"><i class="bi bi-plus-circle"></i> <?= __('Add new') ?></a>
</div>

<p>
    <?= __('You can use the snippets in the pages. Just use the key, which is replaced later with the snippet content.') ?>
</p>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('Key') ?></th>
            <th scope="col"><?= __('Status') ?></th>
            <th scope="col"><?= __('Options') ?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($snippets as $snippet) {
            ?>
            <tr>
                <td><?= $snippet->getId() ?></td>
                <td><?= $snippet->getName() ?></td>
                <td><code><pre>&lt;?= $this->getSnippet('<?= $snippet->getKey() ?>') ?&gt;</pre></code></td>
                <td>
                    <span class="badge bg-<?= $snippet->isActive() ? 'success' : 'danger' ?>"><?= $snippet->isActive() ? __('Yes') : __('No') ?></span>
                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="<?= __('Options') ?>">
                        <a class="btn btn-primary btn-sm" href="<?= $this->getAbsoluteUrl('/admin/snippets/edit/%s', $snippet->getSecret()) ?>">
                            <i class="bi bi-gear"></i> <?= __('Edit') ?></a>
                        <a data-confirm="<?= __('Delete the snippet %s', $snippet->getKey()) ?>" class="btn btn-danger btn-sm" href="<?= $this->getAbsoluteUrl('/admin/snippets/delete/%s',
                            $snippet->getSecret
                        ()) ?>">
                            <i class="bi bi-trash"></i> <?= __('Delete')?>
                        </a>
                    </div>
                </td>
            </tr>
            <?php


        }
        ?>
        </tbody>

    </table>
</div>