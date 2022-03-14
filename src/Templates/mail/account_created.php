<?php

use basteyy\Webstatt\Models\Entities\UserEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var UserEntity $user */


?>
<h1><?= __('Hello %s', $user->getAnyName()) ?></h1>

<p>
    <?= __('You receive that e-mail, because an new account was created. You can login here: %s', $this->getAbsoluteUrl('/admin')) ?>
</p>
