<?php

use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::acp', ['title' => __('Webstatt Self Update')]);

?>

<h1 class="my-md-5"><i class="mx-md-2 bi bi-emoji-smile-upside-down"></i> <?= __('Webstatt Self Update') ?></h1>

<p>
    <?= __('In case you installed webstatt via composer requirements, you can try to update webstatt to the latest version. Be aware, that that operation can probably broke your system. Make sure you have access via ftp or ssh to the website, to fix errors.') ?>
</p>

<form method="post" action="<?= $this->getCurrentUrl() ?>" autocomplete="off">

    <?php if(!str_contains($process, 'basteyy/webstatt')) { ?>
    <div class="alert alert-danger" role="alert">
        <?= __('<strong>Webstatt is not installed via composer!</strong> Ask your admin for more information and help.') ?>
    </div>
    <?php } ?>


    <pre><code><?php


            echo str_replace('basteyy/webstatt', '<strong class="bg-danger text-warning">basteyy/webstatt</strong>', $process);

            ?></code></pre>

    <button <?= str_contains($process, 'basteyy/webstatt') ? '':'disabled ' ?> type="submit" class="btn btn-primary">
        <?= __('Update') ?>
    </button>
</form>

