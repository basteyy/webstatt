<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Files')]);


/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var UserAbstraction $User */
$User = $this->getUser();
?>


<h1>
    <?= __('Files overview') ?>
</h1>

<hr/>

