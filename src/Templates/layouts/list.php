<?php

use basteyy\Webstatt\Models\Entities\PageEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var PageEntity $page */

$this->layout('Webstatt::acp', ['title' => __('Layout overview')]);
?>

<p class="text-lg-end">
    <a class="btn btn-primary" href="<?= $this->getAbsoluteUrl('/admin/layouts/add') ?>"><i class="bi bi-plus-circle-dotted"></i> <?= __('New layout') ?></a>
</p>

<h1 class="my-md-5"><?= __('Manage the layouts') ?></h1>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= __('Name') ?></th>
            <th scope="col"><?= __('Status') ?></th>
            <th scope="col"><?= __('Options') ?></th>
        </tr>
        </thead>

        <tbody>
        <?php
        /** @var \basteyy\Webstatt\Models\Entities\LayoutEntity $layout */
        foreach ($layouts as $layout) {
            ?>
            <tr>
                <td><?= $layout->getId() ?></td>
                <td><?= $layout->getName() ?></td>
                <td><?= $layout->isActivated() ? __('<span class="badge rounded-pill bg-success">Online</span>') : __('<span class="badge rounded-pill bg-danger">Offline</span>')
                    ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="<?= __('Options') ?>">
                        <a class="btn btn-primary btn-sm" href="<?= $this->getAbsoluteUrl('/admin/layouts/edit/%s', $layout->getSecret()) ?>"><i class="bi bi-gear"></i>
                            <?= __('Edit') ?></a>
                        <a class="btn btn-danger btn-sm" href="<?= $this->getAbsoluteUrl('/admin/layouts/delete/%s_%s',
                            $layout->getSecret(), $layout->getId()) ?>"><i class="bi
                        bi-search"></i> <?= __('Delete')
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

<h2 class="h4">
    <?= __('What is a layout?') ?>
</h2>

<p>
    <?= __('A layout defines the design of the pages of your website. Thanks to your layouts, you have to do work just once, and you can change all relevant aspects of the visual appearance by your website in a single file. You can create an unlimited number of layouts. Learn more about layouts on the wiki: <a href="https://github.com/basteyy/webstatt/wiki/003-Layouts">github.com/basteyy/webstatt/wiki/003-Layouts</a>.') ?>
</p>