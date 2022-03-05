<?php

use basteyy\Webstatt\Enums\ContentType;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Add new content')]);
?>

<h1><?= __('Create a new content page') ?>></h1>

<p>
    Ein "Inhalt" ist quasi eine Seite deiner Website. Eine solche Seite setzt sich aus verschiedenen Elementen zusammen. Neben dem wesentlichstem Element, dem Body (der ganz
    wesentlichen Information), gibt es auch die Metatags und die Adresse (url).
</p>


<form class="row g-5" method="post" action="<?= $this->getCurrentUrl() ?>">
    <div class="col-md-4">
        <h2><?= __('Basic data') ?></h2>

        <div class="row g-3">
            <div class="col-12">
                <label for="_url" class="form-label"><?= __('Url of the page') ?></label>
                <input type="text" class="form-control" id="_url" name="url" placeholder="<?= __('Url of the page') ?>" required>
            </div>
            <div class="col-12">
                <label for="_name" class="form-label"><?= __('Intern name of the page') ?></label>
                <input type="text" class="form-control" id="_name" name="name" placeholder="<?= __('Intern name of the page') ?>" required>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <h2><?= __('SEO data') ?></h2>

        <div class="row g-3">

            <div class="col-12">
                <label for="_title" class="form-label"><?= __('SEO Title Tag') ?></label>
                <input type="text" class="form-control" id="_title" name="title" placeholder="<?= __('SEO Title Tag') ?>" value="">
            </div>

            <div class="col-12">
                <label for="_description" class="form-label"><?= __('SEO Description Tag') ?></label>
                <input type="text" class="form-control" id="_description" name="description" placeholder="<?= __('SEO Description Tag') ?>" value="">
            </div>

            <div class="col-12">
                <label for="_keywords" class="form-label"><?= __('SEO Keywords Tag') ?></label>
                <input type="text" class="form-control" id="_keywords" name="keywords" placeholder="<?= __('SEO Keywords Tag') ?>" value="">
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <h2><?= __('Settings') ?></h2>
        <div class="row g-3">
        <div class="col-12">
            <label for="_type" class="form-label"><?= __('Type') ?></label>
            <select class="form-select" id="_type" name="contentType">
                <optgroup label="<?= __('Please select') ?>">
                    <?php
                    foreach (ContentType::cases() as $case) {
                        printf('<option value="%s">%s</option>', $case->name, $case->value);
                    }
                    ?>
                </optgroup>
            </select>

            <p class="text-xs mt-2 alert alert-info">
                <?= __('<strong>Content types</strong> define the processing of the content page.') ?>
            </p>
        </div>
        <?php
        if($this->getLayouts() !== null && count($this->getLayouts()) > 0 ) {
            echo $this->fetch('Webstatt::content/layouts_select');
        }
        ?></div>
    </div>



    <div class="col-12">
        <input type="submit" class="btn btn-primary" value="<?= __('Save') ?>"/></div>
</form>


