<?php

use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var PageEntity $page */

$this->layout('Webstatt::layouts/acp', ['title' => __('Overview of your pages')]);
?>

<h1 class="my-md-5"><?= __('Manage your pages') ?></h1>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= __('Startpage') ?></th>
            <th scope="col"><?= __('Online') ?></th>
            <th scope="col"><?= __('Page Type') ?></th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('URL') ?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach ($pages as $page) {
            ?>
            <tr>
                <td><?= $page->getId() ?></td>
                <td><?= $page->getStartpage() ? '<span class="badge rounded-pill bg-primary ">'.__('yes').'</span>' : '' ?></td>
                <td><?= $page->getOnline() ? '<span class="badge rounded-pill bg-success">' . __('yes') .'</span>' : '<span class="badge rounded-pill bg-danger">'.__('no').'</span>'?></td>
                <td><?= $page->getPageType()->value ?></td>
                <td><?= $page->getName() ?></td>
                <td>
                    <ul>
                        <li><?= __('Url') ?>: <?= $page->getUrl() ?></li>
                        <li><?= __('Title') ?>: <?= $page->getTitle() ?></li>
                    </ul>
                </td>
                <td>
                    <div class="btn-group" role="group" aria-label="<?= __('Options') ?>">
                        <a class="btn btn-primary btn-sm" href="<?= $this->getAbsoluteUrl('/admin/pages/edit/%s', $page->getSecret()) ?>"><i class="bi bi-gear"></i>
                            <?= __('Edit') ?></a>
                        <a target="_blank" class="btn btn-secondary btn-sm" href="<?= $this->getAbsoluteUrl($page->getUrl()) ?>"><i class="bi bi-search"></i> <?= __('View') ?></a>
                        <a target="_blank" class="btn btn-danger btn-sm" href="<?= $this->getAbsoluteUrl($page->getUrl()) ?>"><i class="bi bi-search"></i> <?= __('Delete')
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