<?php

use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var UserAbstraction $User */
$User = $this->getUser();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title><?= $title ?? 'ACP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" media="(prefers-color-scheme: light)">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-dark-5@1.1.3/dist/css/bootstrap-night.min.css" rel="stylesheet" media="(prefers-color-scheme: dark)">
    <link href="https://rsms.me/inter/inter.css" rel="stylesheet" crossorigin="anonymous">
    <style>
        :root {
            --bs-body-font-family: 'Inter var', sans-serif;
            --webstatt-green-rgb: 139, 180, 26;
            --webstatt-text-rgb: 255,255,255;
            --webstatt-green: rgb(var(--webstatt-green-rgb), 1);
            --webstatt-not-green: rgb(var(--webstatt-text-rgb), 1);

        }
        @media(prefers-color-scheme: dark){
            :root {
                --webstatt-green: rgb(var(--webstatt-green-rgb), .3);
                --webstatt-not-green: rgb(var(--webstatt-text-rgb), .5);
            }
        }

        .above-navbar a, .above-navbar, .above-navbar a:hover {
            color: var(--webstatt-not-green);
        }

        .above-navbar {
            background-color: var(--webstatt-green);
        }
    </style>
    <meta name="color-scheme" content="light dark">

</head>
<body>

<nav class="text-end p-2 above-navbar d-flex justify-content-between align-items-baseline">
    <span><?= __('Hi %s', $this->getUser()->getAnyName()) ?></span>
    <a class="nav-link" href="<?= $this->getAbsoluteUrl('/admin/logout?t=') ?><?= time() ?>">
        <i class="mx-md-2 bi bi-box-arrow-right"></i> <?= __('Logout') ?>
    </a>
</nav>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-xxl">
        <a class="navbar-brand" href="<?= $this->getAbsoluteUrl('/admin/dashboard') ?>">
            <?= __('Webstatt') ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item mx-lg-3">
                    <a class="nav-link" href="<?= $this->getAbsoluteUrl('/admin/dashboard') ?>" role="button"><i class="mx-md-2 bi bi-speedometer"></i> <?= __('Dashboard') ?>
                    </a>
                </li>

                <li class="nav-item dropdown mx-lg-3">
                    <a class="nav-link dropdown-toggle" href="<?= $this->getAbsoluteUrl('/admin/pages') ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="mx-md-2 bi bi-newspaper"></i> <?= __('Content') ?>
                    </a>
                    <ul class="dropdown-menu p-md-4" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/pages') ?>" title="<?= __('Pages Overview') ?>">
                                <i class="mx-md-2 bi bi-view-list"></i> <?= __('List the content') ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/pages/add') ?>" title="<?= __('Create a new Page') ?>">
                                <i class="mx-md-2 bi bi-plus-circle"></i> <?= __('Add new content') ?>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item dropdown mx-lg-3">
                    <a class="nav-link dropdown-toggle" href="<?= $this->getAbsoluteUrl('/admin/files') ?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="mx-md-2 bi bi-download"></i> <?= __('Files') ?>
                    </a>
                    <ul class="dropdown-menu p-md-4" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/files') ?>" title="<?= __('Manage uploaded files in your website') ?>">
                                <i class="mx-md-2 bi bi-newspaper"></i> <?= __('Manage files') ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/files/upload') ?>" title="<?= __('Upload new files') ?>">
                                <i class="mx-md-2 bi bi-newspaper"></i> <?= __('Upload files') ?>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item dropdown mx-lg-3">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mx-md-2 bi bi-person"></i> <?= __('Your Account') ?>
                    </a>
                    <ul class="dropdown-menu p-md-4" aria-labelledby="navbarDropdown">

                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/account') ?>" title="<?= __('Manage your profil') ?>">
                                <i class="mx-md-2 bi bi-person-circle"></i> <?= __('Your Profile') ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/account/email') ?>">
                                <i class="mx-md-2 bi bi-envelope"></i> <?= __('Change your E-Mail') ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/account/password') ?>">
                                <i class="mx-md-2 bi bi-unlock"></i> <?= __('Change your password') ?>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item py-md-3" href="<?= $this->getAbsoluteUrl('/admin/account/settings') ?>">
                                <i class="mx-md-2 bi bi-gear"></i> <?= __('Change your settings') ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <?php
            if (isset($additional_admin_nav_items)) {
                foreach ($additional_admin_nav_items as $item) {
                    echo $item;
                }
            }
            ?>
            <ul class="navbar-nav me-0 mb-2 mb-lg-0">

                <?php
                if($this->getUser()->isAdmin()) {
                    echo $this->fetch('Webstatt::layouts/partials/admin_settings_dropdown');
                }
                ?>

                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= $this->getAbsoluteUrl('/') ?>" title="<?= __('Go to the website') ?>">
                        <i class="mx-md-2 bi bi-globe"></i> <?= __('Website') ?>
                    </a>
                </li>

            </ul>
            <!--<form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>-->
        </div>
    </div>
</nav>

<div class="min-vh-100 d-flex flex-column
                justify-content-between pt-4">
    <div class="container-xxl ">
        <?php
        $__all_messages = FlashMessages::getAllMessages();

        if (count($__all_messages) > 0) {
            if (isset($__all_messages[FlashMessages::$errorMessages])) {
                foreach ($__all_messages[FlashMessages::$errorMessages] as $__error_message) {
                    printf('<div class="alert alert-danger" role="alert">%s</div>', $__error_message);
                }
            }
            if (isset($__all_messages[FlashMessages::$successMessages])) {
                foreach ($__all_messages[FlashMessages::$successMessages] as $__success_message) {
                    printf('<div class="alert alert-info" role="alert">%s</div>', $__success_message);
                }
            }
        }
        ?>



        <?= $this->section('content') ?>
    </div>


    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-8 d-flex align-items-center">
                <p class="text-secondary text-opacity-50">
                    <a href="https://webstatt.org" class="me-2 text-muted text-decoration-none fw-bold">
                        Webstatt
                    </a>
                    - ein
                    <a class="text-muted text-decoration-none text-opacity-50" href="https://eiweleit.de">
                        Sebastian Eiweleit
                    </a>
                    Projekt. CC BY-SA 4.0. Made with 🌶️ in <a class="text-muted text-decoration-none text-opacity-50" href="https://europa.eu">Europe</a>.
                </p>
            </div>
            <div class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <p>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#webstattInfoModel"><i class="mx-md-2 bi bi-info"></i> Webstatt</button>
                </p>
            </div>


        </footer>
    </div>
</div>

<div class="modal fade" id="webstattInfoModel" tabindex="-1" aria-labelledby="webstattInfoModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="webstattInfoModelLabel"><?= __('Yes! You are using Webstatt!') ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-info" role="alert">
                    <?= __('Thanks for using webstatt!') ?>
                </div>
                <p class="lh-lg">
                    <?= __('Webstatt is a content management system. This allows you to run websites - the way you\'re probably doing right now.') ?>
                </p>

                <h3 class="h5 fw-bold"><?= __('You') ?> + <?= $configService->agency_name ?> + Webstatt = 🤝</h3>
                <p class="lh-lg">
                    <?= __('Your website was set up by <strong>%1$s</strong>. In case, you find help here: <a href="mailto:%2$s">%2$s</a>. For more information, check out <a href="%3$s">%3$s</a>.',
                        $configService->agency_name,
                        $configService->agency_email,
                        $configService->agency_website
                    ) ?>
                </p>

                <hr/>

                <h3 class="h6 fw-bold"><?= __('More about webstatt') ?></h3>
                <p>
                    <a href="https://webstatt.org" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-globe"></i> <?= __('Website') ?></a>
                    <a href="https://github.com/basteyy/webstatt" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-github"></i> <?= __('Github') ?></a>
                    <a href="https://webstatt.org" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-chat"></i> <?= __('Community') ?></a>
                    <a href="https://www.patreon.com/webstatt" class="btn btn-secondary btn-sm"><i class="mx-md-2 bi bi-emoji-neutral"></i> <?= __('Support') ?></a>
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Fine') ?></button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="<?= $this->getAbsoluteUrl('/js/vendor/confirmbutton.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"/>


</body>
</html>
