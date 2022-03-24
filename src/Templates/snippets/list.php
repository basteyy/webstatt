<?php

use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\SnippetEntity $snippet */

$this->layout('Webstatt::layouts/acp', ['title' => __('Overview of your snippets')]);
?>

<h1 class="my-md-5"><?= __('Manage the snippets') ?></h1>

<div class="float-md-end">
    <a class="btn btn-primary" title="<?= __('Add a new snippet') ?>" href="<?= $this->getAbsoluteUrl('/admin/pages/snippets/add') ?>"><i class="bi bi-plus-circle"></i> <?= __('Add new') ?></a>
</div>

<p>
    <?= __('You can use the snippets in the pages. Just use the key, which is replaced later with the snippet content.') ?>
</p>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= __('Key') ?></th>
            <th scope="col"><?= __('Options') ?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($snippets as $snippet) {
            ?>
            <tr>
                <td><?= $snippet->getId() ?></td>
                <td><?= $snippet->getKey() ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="<?= __('Options') ?>">
                        <a class="btn btn-primary btn-sm" href="<?= $this->getAbsoluteUrl('/admin/pages/edit/%s', $snippet->getSecret()) ?>"><i class="bi bi-gear"></i>
                            <?= __('Edit') ?></a>
                        <a target="_blank" class="btn btn-secondary btn-sm" href="<?= $this->getAbsoluteUrl($snippet->getUrl()) ?>"><i class="bi bi-search"></i> <?= __('View') ?></a>
                        <a target="_blank" class="btn btn-danger btn-sm" href="<?= $this->getAbsoluteUrl($snippet->getUrl()) ?>"><i class="bi bi-search"></i> <?= __('Delete')
                            ?></a>
                    </div>
                </td>
            </tr>
            <?php


        }
        ?>
        </tbody>

    </table>
</div>