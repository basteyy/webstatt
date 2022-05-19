<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Dashboard')]);


/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var \basteyy\Webstatt\Models\Entities\UserEntity $User */
$User = $this->getUser();


if ($this->getUser()->isAdmin()) {
    $mail_config = $this->getConfig()->getMailConfig();

    if (!isset($mail_config['activate_mail_system']) || !$mail_config['activate_mail_system']) {
        printf('<p class="alert alert-warning">%s</p>', __('The E-Mail System isn\'t acitacted yet. <a href="/admin/settings/email">Activate it now</a>'));
    }

}

?>


<?php
if (!$User->hasName()) { ?>
    <p class="alert alert-warning">
        <?= __('You have not set a name to your profile. To free the full potential, please enter name'); ?>
    </p>
<?php
} ?>

<h1 class="my-md-5">
    <?= __('Dashboard') ?>
</h1>

<p class="fs-2">
    <?= __('Welcome to the dashboard of your website. You are using webstatt. Webstatt is still under development. So feel free to contribute.') ?>
</p>

<hr class="my-md-5"/>


<div class="container">
    <div class="row g-2 g-lg-5">
        <div class="col-md">
            <a href="" class="btn btn-primary btn-sm float-end"><i class="bi bi-plus-square"></i> <?= __('New') ?></a>
            <h2><?= __('Content') ?></h2>


        </div>
        <div class="col-md">
            <h2><?= __('Users') ?></h2>
            <p class="text-xs">
                <?= __('There are %s users in the system', count($users)) ?>
            </p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col"><?= __('ID') ?></th>
                    <th scope="col"><?= __('User') ?></th>
                    <th scope="col"><?= __('Last login') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var \basteyy\Webstatt\Models\Entities\UserEntity $user */
                foreach ($users as $user) {

                    printf('<tr><td>%3$s</td><td><a href="/admin/u/%4$s" title="View Profile">%1$s</a></td><td>%2$s</td></tr>',
                        $user->getAnyName(),
                        $user->getLastlogin(),
                        $user->getId(),
                        $user->getSecret()
                    );

                    //print __('%2$s', '/admin/u/' . $user->getSecret(), $user->getAnyName()) . PHP_EOL;
                    //print __('<a href="%1$s">%2$s</a>', '/admin/u/' . $user->getSecret(), $user->getAnyName()) . PHP_EOL;

                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <hr class="my-md-5"/>

    <div class="row g-2 g-lg-5">

        <div class="col-md">

            <h2 class="h5"><?= __('Your account') ?></h2>


            <table class="table">
                <tbody>
                <tr>
                    <td><?= __('Name') ?></td>
                    <td><?= $this->getUser()->hasName() ? $this->getUser()->getName() : '<a href="/admin/account">Set name</a>' ?></td>
                </tr>

                <tr>
                    <td><?= __('E-Mail') ?></td>
                    <td><?= $this->getUser()->email ?></td>
                </tr>

                </tbody>
            </table>

            <a href="/admin/u/<?= $this->getUser()->getSecret() ?>" class="btn btn-secondary btn-sm float-end"><i
                        class="mx-md-2 bi bi-person-circle"></i> <?= __('Show your profile')
                ?></a>
        </div>

        <div class="col-md">
            <h3 class="h5"><?= __('Webstatt version') ?></h3>

            <script>
                String.prototype.format = function () {
                    var args = arguments;
                    return this.replace(/{([0-9]+)}/g, function (match, index) {
                        // check if the argument is there
                        return typeof args[index] == 'undefined' ? match : args[index];
                    });
                };

                let url = 'https://api.github.com/repos/basteyy/webstatt/commits';

                window.setTimeout(function(){
                    fetch(url)
                        .then((response) => {
                            return response.json();
                        })
                        .then((data) => {
                            document.querySelector('#wversion').innerHTML = '<?= __('Last version <strong>{0}</strong> from {1} <hr /> Info: {2} <hr />{3}') ?>'
                                .format(
                                    data[0]['sha'].substring(0,7),
                                    data[0]['commit']['author']['date'],
                                    data[0]['commit']['message'],
                                    '<a href="' + data[0]['html_url'] + '"><?= __('More details on GitHub.com') ?></a>'
                                );
                        })
                }, 1000);
            </script>
            <div id="wversion"></div>

            <a href="https://github.com/basteyy/webstatt" class="btn btn-secondary btn-sm float-end"><i
                        class="mx-md-2 bi bi-github"></i> <?= __('Visit GitHub')
                ?></a>

        </div>

        <div class="col-md">

            <h3 class="h5"><?= __('You need help?') ?></h3>
            <p class="lh-lg">
                <?= __('<strong>%s</strong> takes care about any problems you may have.', $configService->agency_name) ?>
            </p>
            <p>
                <i class="mx-md-2 bi bi-envelope-heart"></i>
                <a href="mailto:<?= $configService->agency_email ?>">
                    <?= $configService->agency_email ?>
                </a>

                <br/>

                <i class="mx-md-2 bi bi-globe"></i>
                <a href="<?= $configService->agency_website ?>">
                    <?= $configService->agency_website ?>
                </a>
            </p>
        </div>
    </div>

</div>



