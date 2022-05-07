<?php

use basteyy\Webstatt\Enums\PageType;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Add a new page')]);
?>

<h1 class="my-md-5"><?= __('Create a new page') ?></h1>

<p><?= __('A page is a single website, which will delivered under the url, you type in.') ?></p>


<form class="row g-5" method="post" action="<?= !$this->getLayouts() ? $this->getCurrentUrl():'#' ?>" id="_createPageForm">
    <div class="col-md-4">
        <h2><?= __('Basic Page Data') ?></h2>

        <div class="row g-3">
            <div class="col-12">

                <label for="_url" class="form-label"><?= __('Url of the page') ?></label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="_url_addon"><?= $this->getConfig()->website_url ?></span>
                    <input type="text" class="form-control" id="_url" name="url" placeholder="<?= __('Url of the page') ?>" required aria-describedby="_url_addon">
                </div>
                <p class="alert alert-danger visually-hidden" id="_emptyUrlError">
                    <?= __('You did not enter a valid url. In case this page should be the startpage, enable to checkbox below')?>
                </p>


                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="_startpage" name="startpage">
                    <label class="form-check-label" for="_startpage"><?= __('Page is homepage/startpage of the website') ?></label>
                </div>
            </div>
            <div class="col-12">
                <label for="_name" class="form-label"><?= __('Intern name of the page') ?></label>
                <input type="text" class="form-control" id="_name" name="name" placeholder="<?= __('Intern name of the page') ?>" required>
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="_is_page_online" name="online">
                    <label class="form-check-label" for="_is_page_online"><?= __('Page is online') ?></label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <h2><?= __('SEO Page Information') ?></h2>

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
                <select class="form-select" id="_type" name="PageType">
                    <optgroup label="<?= __('Please select') ?>">
                        <?php
                        foreach (PageType::cases() as $case) {
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
            if ($this->getLayouts()) {
                echo $this->fetch('Webstatt::pages/layouts_select');
            } else {
                printf('<div class="alert alert-danger" role="alert">%s</div>', __('You cannot create a page without a layout. Create a new layout (Content - Layouts) or define one inside the code'));
            }
            ?></div>
    </div>


    <div class="col-12">
        <input <?= !$this->getLayouts() ? 'disabled':'' ?> type="submit" class="btn btn-primary" value="<?= __('Save') ?>"/>
    </div>
</form>

<script>
    let _url = document.querySelector('input#_url'),
        _startpage = document.querySelector('input#_startpage'),
        _emptyUrlError = document.querySelector('#_emptyUrlError');

    _startpage.addEventListener('change', function () {
        console.log('Hide Warning');
        _emptyUrlError.classList.add('visually-hidden');
    })

    document.querySelector('form#_createPageForm').addEventListener('submit', function (e) {
        if(!_startpage.checked && ( _url.value === '/'  || _url.value.length < 2 )) {
            e.preventDefault();
            _emptyUrlError.classList.remove('visually-hidden');
            return false;
        }
    })
</script>

