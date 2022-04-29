<?php

use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/extern', ['title' => __('Accept invitation')]);
?>

<main class="w-100 m-auto xxl">
    <h1 class="h3"><?= __('You are invited to join') ?></h1>

    <p>
        <?= __('The link you followed allows you to signup for an account. Insert your e-mail address and choose a strong password.') ?>
    </p>

    <hr/>

    <form method="post" action="<?= $this->getCurrentUrl() ?>">

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-floating mb-3">
                    <input class="form-control" name="email[]" type="email" required autofocus placeholder="Email" id="email1"/>
                    <label for="email1"><?= __('E-Mail address') ?></label>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="form-floating mb-3">
                    <input class="form-control" name="email[]" type="email" required autofocus placeholder="Email" id="email2"/>
                    <label for="email2"><?= __('Confirm your E-Mail address') ?></label>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password1" required name="password[]" autocomplete="no" placeholder="Password">
                    <label for="password1"><?= __('Password') ?></label>
                </div>

            </div>
            <div class="col-12 col-md-6">

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password2" required name="password[]" autocomplete="no" placeholder="Password">
                    <label for="password2"><?= __('Confirm your new password') ?></label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-check m-3">
                    <input class="form-check-input float-md-none" type="checkbox" value="yes" id="terms" name="terms" required/>
                    <label class="form-check-label" for="terms">
                        <?= __('I accept the <a target="_blank" href="%s">terms</a>.', $this->getAbsoluteUrl('/admin/terms')) ?>
                    </label>
                </div>
            </div>
        </div>

        <button class="btn btn-lg btn-primary" type="submit"><?= __('Sign up') ?></button>

        <hr/>

        <p>
            <?= __('You already have an account? You can sign in <a href="/admin">here</a>.') ?>
        </p>

    </form>
</main>