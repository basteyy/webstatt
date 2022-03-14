<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Dashboard')]);


/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $User */
$User = $this->getUser();

?>


<?php if(!$User->hasName() ) { ?>
    <p class="alert alert-warning">
        <?= __('You have not set a name to your profile. To free the full potential, please enter name'); ?>
    </p>
<?php } ?>

<h1 class="my-md-5">
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
            <p class="text-xs">
                <?= __('There are %s users in the system', count($users)) ?>
            </p>
            <hr />
            <?php
            /** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */
            foreach ( $users as $user) {
                print __('%2$s', '/admin/u/' . $user->getSecret(), $user->getAnyName()) . PHP_EOL;
                //print __('<a href="%1$s">%2$s</a>', '/admin/u/' . $user->getSecret(), $user->getAnyName()) . PHP_EOL;

            }
            ?>
        </div>
        <div class="col-md">
            <h2><?= __('Your account') ?></h2>

            <p>
                <?= __('Name') ?>: <?= $this->getUser()->hasName() ? $this->getUser()->getName() : '<a href="">Set name</a>'?>
            </p>

            <p>
                <?= __('E-Mail') ?>: <?= $this->getUser()->email ?>
            </p>


            <hr/>
            <p>
                <a href="/admin/u/<?= $this->getUser()->getSecret() ?>" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-person-circle"></i> <?= __('Show your profile')
                    ?></a>
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


