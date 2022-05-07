<?php

use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::extern', ['title' => __('Request your password')]);
?>
<main class="w-100 m-auto xxl">

    <h1><?= __('Lost your password? Request a new one!') ?></h1>

    <p>
        You can use the following form to reset your password.
    </p>

    <form method="post" action="<?= $this->getCurrentUrl() ?>">
        <div class="form-floating mb-3">
            <input class="form-control" name="email" type="email" required autofocus placeholder="Email" id="_email"/>
            <label for="_email"><?= __('E-Mail address') ?></label>
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

        <button class="btn btn-lg btn-primary" type="submit"><?= __('Request') ?></button>

        <hr/>
        <a href="<?= $this->getAbsoluteUrl('/admin') ?>"><?= __('Back to the login') ?></a>
    </form>
</main>