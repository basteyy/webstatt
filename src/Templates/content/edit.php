<?php

use basteyy\Webstatt\Enums\ContentType;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit a content page')]);

/** @var \basteyy\Webstatt\Models\Abstractions\PageAbstraction $page */
?>

<h1><?= __('Edit a content page') ?></h1>


<!-- Modal -->
<div class="modal fade" id="data_type_change_limitations" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?= __('The content types are basically processed differently. Changing the content type is thus only possible by higher authorized users, in order to prevent errors or content loss. An alternative to the change is to create a new content page and transfer the content manually.') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Fine') ?></button>
            </div>
        </div>
    </div>
</div>


<form class="row g-5" method="post" action="<?= $this->getCurrentUrl() ?>">
    <div class="col-md-4">
        <h2><?= __('Basic data') ?></h2>

        <div class="row g-3">
            <div class="col-12">
                <label for="_url" class="form-label"><?= __('Url of the page') ?></label>
                <input type="text" class="form-control" id="_url" name="url" placeholder="<?= __('Url of the page') ?>" required value="<?= $page->getUrl()?>" />
            </div>
            <div class="col-12">
                <label for="_name" class="form-label"><?= __('Intern name of the page') ?></label>
                <input type="text" class="form-control" id="_name" name="name" placeholder="<?= __('Intern name of the page') ?>" required value="<?= $page->getName() ?>">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h2><?= __('SEO data') ?></h2>

        <div class="row g-3">

            <div class="col-12">
                <label for="_title" class="form-label"><?= __('SEO Title Tag') ?></label>
                <input type="text" class="form-control" id="_title" name="title" placeholder="<?= __('SEO Title Tag') ?>" value="<?= $page->getTitle()?>">
            </div>

            <div class="col-12">
                <label for="_description" class="form-label"><?= __('SEO Description Tag') ?></label>
                <input type="text" class="form-control" id="_description" name="description" placeholder="<?= __('SEO Description Tag') ?>" value="<?= $page->getDescription()?>">
            </div>

            <div class="col-12">
                <label for="_keywords" class="form-label"><?= __('SEO Keywords Tag') ?></label>
                <input type="text" class="form-control" id="_keywords" name="keywords" placeholder="<?= __('SEO Keywords Tag') ?>" value="<?= $page->getKeywords()?>">
            </div>

            <?php
            if(0 !== $this->getConfig()->pages_max_versions ) {
                echo $this->fetch('Webstatt::content/editors/versions', ['page' => $page]);
            }
            ?>
        </div>
    </div>

    <div class="col-md-4">

        <div class="col-12">

            <p class="text-xs mt-2 alert alert-danger">
                <?= __('You cannot change the content type of the document. <a href="#" data-bs-toggle="modal" data-bs-target="#data_type_change_limitations">Read here</a> why.') ?>
            </p>

            <label for="_type" class="form-label"><?= __('Type') ?></label>
            <select class="form-select" id="_type" name="contentType" disabled>
                <optgroup label="<?= __('Please select') ?>">
                    <?php
                    foreach (ContentType::cases() as $case) {
                        printf('<option value="%1$s"%3$s>%2$s</option>', $case->name, $case->value, $page->getContentType() === $case ? ' selected' : '');
                    }
                    ?>
                </optgroup>
            </select>
            <p class="text-xs mt-2 alert alert-info">
                <strong>Inhaltstypen</strong> legt fest, wei der Inhalte vom System verarbeitet wird. <code><?=
                    ContentType::MARKDOWN->value ?></code> bedeutet, dass der Inhalt danach als <a href="https://www.markdownguide.org"
                                                                                                   target="_blank">Markdown</a> verarbeitet wird.
                Die zweite Variante ist aktuell <code><?= ContentType::HTML_PHP->value ?></code>, bei welchen eine umfängliche Bearbeitung möglich ist.
            </p>
        </div>
        <?php
        if($this->getLayouts() !== null && count($this->getLayouts()) > 0 ) {
            echo $this->fetch('Webstatt::content/layouts_select', ['page' => $page]);
        }
        ?>
    </div>

    <div class="col-12 text-end"><input type="submit" class="btn btn-primary" value="<?= __('Save') ?>"/></div>

    <?php
    if($page->getContentType() === ContentType::MARKDOWN) {
        echo $this->fetch('Webstatt::content/editors/markdown', ['page' => $page]);
    } elseif ( $page->getContentType() === ContentType::HTML_PHP) {
        echo $this->fetch('Webstatt::content/editors/html_php', ['page' => $page]);
    } else {
        print __('Unable to edit content. Content Type %s is not supported.', $page->getContentType()->value);
    }

    ?>

    <div class="col-12"><input type="submit" class="btn btn-primary" value="<?= __('Save') ?>"/></div>
</form>

