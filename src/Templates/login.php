<?php
$this->layout('Webstatt::layouts/extern', ['title' => \basteyy\VariousPhpSnippets\__('Login')]);
?>
<main class="form-signin">
    <form method="post" action="<?= $this->getCurrentUrl() ?>">
        <div class="form-floating mb-3">
            <input class="form-control" name="email" type="email" required autofocus placeholder="Email" id="_email"/>
            <label for="_email"><?= \basteyy\VariousPhpSnippets\__('E-Mail address') ?></label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="_password" required name="password" autocomplete="no" placeholder="Password">
            <label for="_password"><?= \basteyy\VariousPhpSnippets\__('Password') ?></label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit"><?= \basteyy\VariousPhpSnippets\__('Sign in') ?></button>
    </form>
</main>