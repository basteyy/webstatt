<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */

$this->layout('Webstatt::layouts/acp', ['title' => __('Profile of %s', $user->getAnyName())]);


?>

<h1 class="my-md-5">
    <?= __('Profile of %s', $user->getAnyName()) ?>
</h1>

