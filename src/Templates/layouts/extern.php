<?php

use basteyy\Webstatt\Helper\FlashMessages;
use function basteyy\VariousPhpSnippets\__;

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title><?= $title ?? 'Webstatt' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" media="(prefers-color-scheme: light)">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-dark-5@1.1.3/dist/css/bootstrap-night.min.css" rel="stylesheet" media="(prefers-color-scheme: dark)">
    <link href="https://rsms.me/inter/inter.css" rel="stylesheet" crossorigin="anonymous">
    <meta name="color-scheme" content="light dark">
    <style>

        :root {
            --bs-body-font-family: 'Inter var', sans-serif;
        }

        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        main {
            max-width: 18rem;
        }

        main.xxl {
            max-width: 72rem;
        }

        main .form-floating:focus-within {
            z-index: 2;
        }


        @media (min-width: 992px) {
            body>div.w-100 {
                max-width: 72rem;
            }
        }

    </style>
</head>
<body>


<div class="p-4 text-center m-auto w-100">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
