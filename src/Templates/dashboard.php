<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Dashboard')]);


/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var UserAbstraction $User */
$User = $this->getUser();
?>


<h1>
    <?= __('Dashboard') ?>
</h1>

<hr/>


<div class="container">
    <div class="row">
        <div class="col-md">

            <h2><?= __('Content') ?></h2>
        </div>
        <div class="col-md">
            <h2><?= __('Users') ?></h2>
        </div>
        <div class="col-md">
            <h2><?= __('Your account') ?></h2>

            <p>
                <?= __('E-Mail') ?>: <?= $this->getUser()->email ?>
            </p>


            <hr/>
            <p>
                <a href="" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-person-circle"></i> <?= __('Show your profile') ?></a>
            </p>
        </div>
    </div>
</div>

<h3><?= __('You need help?') ?></h3>
<p class="lh-lg">
    <?= __('<strong>%s</strong> takes care about any problems you may have.', $configService->agency_name) ?>
</p>
<p>
    <i class="mx-md-2 bi bi-envelope-heart"></i> <a href="mailto:<?=
    $configService->agency_email ?>"><?=
        $configService->agency_email ?></a><br/>
    <i class="mx-md-2 bi bi-globe"></i> <a href="<?=
    $configService->agency_website ?>"><?=
        $configService->agency_website ?></a>
</p>


