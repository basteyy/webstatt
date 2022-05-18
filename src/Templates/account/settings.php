<?php

use basteyy\Webstatt\Enums\DisplayThemesEnum;
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


    <h2><?= __('Visual Settings') ?></h2>

    <div class="m-2 mt-4">
        <strong><?= __('Select the dark color mode of webstatt') ?></strong>

        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" role="switch" id="display_mode" name="display_mode" value="yes" <?= $User->getDisplayTheme() ===
            DisplayThemesEnum::DARK ? 'checked' : '' ?>>
            <label class="form-check-label" for="display_mode"><?= __('Use dark theme') ?></label>
        </div>

    </div>

    <div class="m-2 mt-4">
        <strong><?= __('Select the editor color scheme') ?></strong>

        <select name="codemirror_theme" class="form-select" aria-label="Select the theme for the code editor (codemirror)">
            <option selected="">default</option>
            <option>3024-day</option>
            <option>3024-night</option>
            <option>abbott</option>
            <option>abcdef</option>
            <option>ambiance</option>
            <option>ayu-dark</option>
            <option>ayu-mirage</option>
            <option>base16-dark</option>
            <option>base16-light</option>
            <option>bespin</option>
            <option>blackboard</option>
            <option>cobalt</option>
            <option>colorforth</option>
            <option>darcula</option>
            <option>dracula</option>
            <option>duotone-dark</option>
            <option>duotone-light</option>
            <option>eclipse</option>
            <option>elegant</option>
            <option>erlang-dark</option>
            <option>gruvbox-dark</option>
            <option>hopscotch</option>
            <option>icecoder</option>
            <option>idea</option>
            <option>isotope</option>
            <option>juejin</option>
            <option>lesser-dark</option>
            <option>liquibyte</option>
            <option>lucario</option>
            <option>material</option>
            <option>material-darker</option>
            <option>material-palenight</option>
            <option>material-ocean</option>
            <option>mbo</option>
            <option>mdn-like</option>
            <option>midnight</option>
            <option>monokai</option>
            <option>moxer</option>
            <option>neat</option>
            <option>neo</option>
            <option>night</option>
            <option>nord</option>
            <option>oceanic-next</option>
            <option>panda-syntax</option>
            <option>paraiso-dark</option>
            <option>paraiso-light</option>
            <option>pastel-on-dark</option>
            <option>railscasts</option>
            <option>rubyblue</option>
            <option>seti</option>
            <option>shadowfox</option>
            <option>solarized dark</option>
            <option>solarized light</option>
            <option>the-matrix</option>
            <option>tomorrow-night-bright</option>
            <option>tomorrow-night-eighties</option>
            <option>ttcn</option>
            <option>twilight</option>
            <option>vibrant-ink</option>
            <option>xq-dark</option>
            <option>xq-light</option>
            <option>yeti</option>
            <option>yonce</option>
            <option>zenburn</option>
        </select>

    </div>


    <div class="mt-5">
        <button type="submit" class="btn btn-primary">
            <?= __('Save') ?>
        </button>
    </div>
</form>

