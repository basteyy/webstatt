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
                    <label for="_name" class="col-sm-4 col-form-label"><?= __('From name') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_name" name="name" autocomplete="disabled" required value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_from" class="col-sm-4 col-form-label"><?= __('From address') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_from" name="from" autocomplete="disabled" required value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_reply" class="col-sm-4 col-form-label"><?= __('Reply to address') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_reply" name="reply" autocomplete="disabled" required value="">
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

                <div class="row mb-3">
                    <label for="_smtp_host" class="col-sm-4 col-form-label"><?= __('SMTP Host') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_smtp_host" name="smtp_host" autocomplete="disabled" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_port" class="col-sm-4 col-form-label"><?= __('SMTP Host Port') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_smtp_port" name="smtp_port" autocomplete="disabled" value="">
                    </div>
                </div>

                <div class="row mb-3 mx-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="_smtp_auth_required" name="smtp_auth_required">
                        <label class="form-check-label" for="_smtp_auth_required"><?= __('SMTP-Auth required') ?></label>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_host" class="col-sm-4 col-form-label"><?= __('SMTP Username') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_smtp_host" name="smtp_host" autocomplete="disabled" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_host" class="col-sm-4 col-form-label"><?= __('SMTP Password') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_smtp_host" name="smtp_host" autocomplete="disabled" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_host" class="col-sm-4 col-form-label"><?= __('SMTP Secure') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_smtp_host" name="smtp_host" autocomplete="disabled" value="">
                    </div>
                </div>

            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

