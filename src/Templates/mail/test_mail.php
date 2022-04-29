<?php

use basteyy\Webstatt\Models\Entities\UserEntity;
use function basteyy\VariousPhpSnippets\__;

/** @var UserEntity $user */

?>
<h1><?= __('Hello friend') ?></h1>

<p>
    <?= __('You received this e-mail as a testmail - %s testet the system. If you can read this, it seems to work.', $user->getAnyName()) ?>
</p>
