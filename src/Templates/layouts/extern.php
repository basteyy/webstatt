<?php

use basteyy\Webstatt\Helper\FlashMessages;
use function basteyy\VariousPhpSnippets\__;

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title><?= $title ?? '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        html,
        body {
            height: 100%;
            font-family: "Ubuntu Light", serif;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .external {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

    </style>

</head>
<body>


<div class="external text-center">
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


    <p class="mt-4 text-secondary">
        &copy; <a href="https://github.com/basteyy/webstatt" class="text-secondary" title="<?= __('Checkout Webstatt - a open-source Content Management System') ?>">Webstatt</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
