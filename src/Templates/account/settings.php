<?php

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Change your settings')]);

/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $User */
$User = $this->getUser();
?>

<h1 class="my-md-5">
    <i class="mx-md-2 bi bi-envelope"></i> <?= __('Change your settings') ?>
</h1>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" id="display_mode" name="display_mode" value="yes" <?= $User->getDisplayTheme() ===
        \basteyy\Webstatt\Enums\DisplayThemesEnum::DARK ? 'checked' : '' ?>>
        <label class="form-check-label" for="display_mode"><?= __('Use dark theme') ?></label>
    </div>

    <div class="mt-2">
        <button type="submit" class="btn btn-primary">
            <?= __('Save') ?>
        </button>
    </div>
</form>

