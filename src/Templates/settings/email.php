<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('E-Mail Settings')]);

/** @var ConfigService $configService */
$configService = $this->getConfig();

?>

<h1 class="my-md-5"><i class="mx-md-2 bi bi-mailbox"></i> <?= __('E-Mail Settings') ?></h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <?php
    if (isset($mail_config['activate_mail_system'])
        && !$mail_config['activate_mail_system']) { ?>
        <p class="alert alert-danger">
            <?= __('Using the mail module is currently <strong>deactivated</strong>.') ?>
        </p>

    <?php
    } ?>


    <div class="row m-5">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="_activate_mail_system" name="activate_mail_system"<?= isset($mail_config['activate_mail_system'])
            && $mail_config['activate_mail_system'] ? ' checked' : '' ?>>
            <label class="form-check-label" for="_activate_mail_system"><?= __('Activate email-system') ?></label>
        </div>
    </div>

    <?php
    if (isset($mail_config['activate_mail_system'])
        && !$mail_config['activate_mail_system']) { ?>
        <hr/>

        <p class="alert alert-warning">
            <?= __('The following settings are only used, when the above mail-activation is activated. Anyway, you can change the settings without activating emailing') ?>
        </p>
    <?php
    } ?>

    <hr/>

    <div class="container-xxl">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <h2 class="h3"><?= __('E-Mail Settings') ?></h2>

                <hr/>

                <div class="row mb-3">
                    <label for="_name" class="col-sm-4 col-form-label"><?= __('From name') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_name" name="name" autocomplete="disabled" required value="<?= $mail_config['name'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_from" class="col-sm-4 col-form-label"><?= __('From address') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_from" name="from" autocomplete="disabled" required value="<?= $mail_config['from'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_reply" class="col-sm-4 col-form-label"><?= __('Reply to address') ?></label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="_reply" name="reply" autocomplete="disabled" required value="<?= $mail_config['reply'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_lang" class="col-sm-4 col-form-label"><?= __('Language') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_lang" name="lang" autocomplete="disabled" required value="<?= $mail_config['lang'] ?? 'de' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_mailer_method" class="col-sm-4 col-form-label"><?= __('Mailer Method (in case you dont use SMTP)') ?></label>
                    <div class="col-sm-8">
                        <select class="form-select" id="_mailer_method" name="mailer_method" aria-label="<?= __('Select sendmail or mail method') ?>">
                            <option selected>Open this select menu</option>
                            <option value="mail"<?= isset($mail_config['mailer_method']) && $mail_config['mailer_method'] === 'mail' ? ' selected' : '' ?>>mail</option>
                            <option value="sendmail"<?= isset($mail_config['mailer_method']) && $mail_config['mailer_method'] === 'sendmail' ? ' selected' : '' ?>>sendmail</option>
                        </select>
                    </div>
                </div>


            </div>
            <div class="col-lg-4 col-md-6">
                <h3 class="h3"><?= __('SMTP-Settings') ?></h3>
                <hr/>
                <div class="row mb-3 mx-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="_smtp_activated" name="smtp_activated"<?= isset($mail_config['smtp_activated']) &&
                        $mail_config['smtp_activated'] ? ' checked' : '' ?>>
                        <label class="form-check-label" for="_smtp_activated"><?= __('Activate SMTP') ?></label>
                    </div>
                </div>

                <div class="row mb-3 mx-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="_smtp_server_debug"
                               name="smtp_server_debug"<?= isset($mail_config['smtp_server_debug']) &&
                        $mail_config['smtp_server_debug'] ? ' checked' : '' ?>>
                        <label class="form-check-label" for="_smtp_server_debug"><?= __('SMTP Server Debug') ?></label>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_host" class="col-sm-4 col-form-label"><?= __('SMTP Host') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_smtp_host" name="smtp_host" autocomplete="disabled" value="<?= $mail_config['smtp_host'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_port" class="col-sm-4 col-form-label"><?= __('SMTP Host Port') ?></label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="_smtp_port" name="smtp_port" autocomplete="disabled" value="<?= $mail_config['smtp_port'] ?? '' ?>">
                    </div>
                </div>

                <div class="row mb-3 mx-1">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="_smtp_auth_required" value="yes"
                               name="smtp_auth_required"<?= isset($mail_config['smtp_auth_required']) && $mail_config['smtp_auth_required'] ? ' checked' : '' ?>>
                        <label class="form-check-label" for="_smtp_auth_required"><?= __('SMTP-Auth required') ?></label>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_username" class="col-sm-4 col-form-label"><?= __('SMTP Username') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_smtp_username" name="smtp_username" autocomplete="disabled" value="<?= $mail_config['smtp_username'] ?? ''
                        ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_password" class="col-sm-4 col-form-label"><?= __('SMTP Password') ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="_smtp_password" name="smtp_password" autocomplete="disabled" value="<?= $mail_config['smtp_password'] ?? ''
                        ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="_smtp_secure" class="col-sm-4 col-form-label"><?= __('SMTP Secure') ?></label>
                    <div class="col-sm-8">
                        <select class="form-select" id="_smtp_secure" name="smtp_secure" aria-label="<?= __('Select Encryption Method') ?>">
                            <option selected>Open this select menu</option>
                            <option value="tls"<?= isset($mail_config['smtp_secure']) && $mail_config['smtp_secure'] === 'tls' ? ' selected' : '' ?>>tls</option>
                            <option value="ssl"<?= isset($mail_config['smtp_secure']) && $mail_config['smtp_secure'] === 'ssl' ? ' selected' : '' ?>>ssl</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <h3 class="h3"><?= __('Send testmail') ?></h3>
                <hr/>
                <label for="test_recipient" class="form-label"><?= __('You can send a testmail to the following recipient') ?></label>
                <input type="email" class="form-control" id="test_recipient" name="test_recipient" autocomplete="disabled">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <?= __('Save') ?>
    </button>
</form>

