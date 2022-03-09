<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('E-Mail Settings')]);

/** @var ConfigService $configService */
$configService = $this->getConfig();

?>

<h1 class="my-md-5"><i class="mx-md-2 bi bi-mailbox"></i> <?= __('E-Mail Settings') ?></h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">


    <p class="alert alert-danger">
        <?= __('Using the mail module is currently <strong>deactivated</strong>.') ?>
    </p>


    <div class="row m-5">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
            <label class="form-check-label" for="flexSwitchCheckDefault"><?= __('Activate email-systen') ?></label>
        </div>
    </div>

    <hr/>

    <p class="alert alert-warning">
        <?= __('The following settings are only used, when the above mail-activation is actiaved. Anyway, you can change the settings without activating emailing') ?>
    </p>

    <hr/>

    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <h2 class="h3"><?= __('E-Mail Settings') ?></h2>

                <hr/>

                <div class="row mb-3">
                    <label for="_alias" class="col-sm-2 col-form-label"><?= __('Your username') ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="_alias" name="alias" autocomplete="disabled" required value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_name" class="col-sm-2 col-form-label"><?= __('Your name') ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="_name" name="name" autocomplete="disabled" required value="">
                    </div>
                </div>


            </div>
            <div class="col-lg-6 col-md-12">
                <h3 class="h3"><?= __('SMTP-Settings') ?></h3>
                <hr/>
                <div class="row mb-3 mx-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault"><?= __('Activate SMTP') ?></label>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

