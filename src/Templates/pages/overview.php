<?php

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => 'Inhalte']);
?>


<h1 class="my-md-5"><?= __('Manage your pages') ?></h1>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('URL') ?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($pages as $_page) {
            $page = new PageAbstraction($_page);

            ?>
            <tr>
                <td><?= $page->getId() ?></td>
                <td><?= $page->getName() ?></td>
                <td>
                    <ul>
                        <li><?= __('Url') ?>: <?= $page->getUrl() ?></li>
                        <li><?= __('Title') ?>: <?= $page->getTitle() ?></li>
                    </ul>
                </td>
                <td>
                    <a class="btn btn-primary btn-sm" href="<?= $this->getAbsoluteUrl('/admin/content/edit/%s', $page->getSecret()) ?>"><?= __('Edit') ?></a>
                    <a target="_blank" class="btn btn-secondary btn-sm" href="<?= $this->getAbsoluteUrl($page->getUrl()) ?>"><i class="mx-md-2 bi bi-search"></i> <?= __('View') ?></a>
                </td>
            </tr>
            <?php


        }
        ?>
        </tbody>

    </table>
</div>