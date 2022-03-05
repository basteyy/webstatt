<?php

use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;

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
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin/dashboard">
            <i class="bi bi-speedometer"></i> Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <?php
                if (isset($additional_admin_nav_items)) {
                    foreach ($additional_admin_nav_items as $item) {
                        echo $item;
                    }
                }
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="/admin/content" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= \basteyy\VariousPhpSnippets\__('Content') ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="/admin/content" title="Übersicht der Inhalte">
                                <i class="bi bi-newspaper"></i> <?= \basteyy\VariousPhpSnippets\__('List the content') ?>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/admin/content/add" title="Einen neuen Inhalt anlegen">
                                <i class="bi bi-plus"></i> <?= \basteyy\VariousPhpSnippets\__('Add new content') ?>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= \basteyy\VariousPhpSnippets\__('Users') ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <li>
                            <a class="dropdown-item" href="/admin/me" title="Dein Profil verwalten">
                                <i class="bi bi-person-circle"></i> <?= \basteyy\VariousPhpSnippets\__('Your Profile') ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="/admin/me/email">
                                <i class="bi bi-envelope"></i> <?= \basteyy\VariousPhpSnippets\__('Change your E-Mail') ?>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="/admin/me/password">
                                <i class="bi bi-unlock"></i> <?= \basteyy\VariousPhpSnippets\__('Change your password') ?>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="/admin/users">
                                <i class="bi bi-people"></i> <?= \basteyy\VariousPhpSnippets\__('User-management') ?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>


            <ul class="navbar-nav me-0 mb-2 mb-lg-0">

                <li class="nav-item mx-lg-5">
                    <a class="nav-item nav-link" href="/admin/logout?t=<?= time() ?>">
                        <i class="bi bi-box-arrow-right"></i> <?= \basteyy\VariousPhpSnippets\__('Logout') ?>
                    </a>
                </li>

                <li class="nav-item mx-lg-5">
                    <a class="nav-link" aria-current="page" href="/" title="Zur Website wechseln">
                        <i class="bi bi-globe"></i> <?= \basteyy\VariousPhpSnippets\__('Website') ?>
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
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-info"></i> Webstatt</button>
                </p>
            </div>


        </footer>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">👍 Du benutzt Webstatt!</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-info" role="alert">
                    <?= \basteyy\VariousPhpSnippets\__('Thanks for using webstatt!') ?>
                </div>
                <p class="lh-lg">
                    Webstatt ist ein Content Management System. Dies ermöglicht den Betrieb von Websites - so, wie du es vermutlich gerade machst.
                </p>

                <h3 class="h4 fw-bold">Du 🤝 <?= $configService->agency_name ?>  🤝 Webstatt</h3>
                <p class="lh-lg">
                    Damit alles gut läuft, wurde
                    die Website für dich von <strong><?= $configService->agency_name ?></strong> aufgesetzt. Bei Problemen kannst du dich gerne an <a href="mailto:<?=
                    $configService->agency_email ?>"><?=
                        $configService->agency_email ?></a> wenden. Mehr Informationen findest du unter <a href="<?=
                    $configService->agency_website ?>"><?=
                        $configService->agency_website ?></a>
                </p>

                <hr />

                <h3 class="h6 fw-bold">Mehr über Webstatt lernen</h3>
                <p>
                    <a href="https://webstatt.org" class="btn btn-secondary btn-sm"><i class="bi bi-globe"></i> Website</a>
                    <a href="https://github.com/basteyy/webstatt" class="btn btn-secondary btn-sm"><i class="bi bi-github"></i> Github</a>
                    <a href="https://webstatt.org" class="btn btn-secondary btn-sm"><i class="bi bi-chat"></i> Community</a>
                    <a href="https://www.patreon.com/webstatt" class="btn btn-secondary btn-sm"><i class="bi bi-emoji-neutral"></i> Unterstützen</a>
                </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= \basteyy\VariousPhpSnippets\__('Fine') ?></button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="<?= $this->getAbsoluteUrl('/js/vendor/confirmbutton.js') ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
</body>
</html>
