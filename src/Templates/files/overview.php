<?php

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Files overview')]);


/** @var ConfigService $configService */
$configService = $this->getConfig();

/** @var UserAbstraction $User */
$User = $this->getUser();

/** @var RecursiveDirectoryIterator $folder */
?>


<h1 class="my-md-5">
    <?= __('Files overview') ?>
</h1>

<hr/>



    <div class="table-responsive">
    <table class="table table-striped table-hover">
    <thead>
    <tr>
        <th scope="col"><?= __('Filename') ?></th>
        <th scope="col"><?= __('Action') ?></th>
    </tr>
    </thead>

    <tbody>
    <?php

    if($upper_folder) {
        ?>
        <tr>
            <td colspan="2"><i class="bi bi-arrow-up"></i> <a href="<?= $this->getAbsoluteUrl('/admin/files') ?>?folder=<?= base64_encode($upper_folder) ?>"><?= $upper_folder ?></a></td>
        </tr>
        <tr>
            <td colspan="2"><i class="bi bi-folder"></i> <?= $folder_real_path ?></td>
        </tr>
    <?php
    }



    /** @var SplFileInfo $file */
    foreach($folder as $file) {

        if($file->isDir()) {
            ?>
            <tr>
                <td><i class="bi bi-folder"></i> <?= $file->getBasename() ?></td>
                <td>
                    <div class="btn-group">
                        <a href="<?= $this->getAbsoluteUrl('admin/files/rename/') ?>?folder=<?=
                        base64_encode($file->getRealPath()) ?>" class="btn btn-sm btn-primary"><i class="bi bi-chat"></i> <?= __('Rename') ?></a>
                        <a href="<?= $this->getAbsoluteUrl('/admin/files') ?>?folder=<?= base64_encode($file->getRealPath()) ?>" class="btn btn-sm btn-primary"><i class="bi
                        bi-door-open"></i> <?= __('Open') ?></a>
                    </div></td>

            </tr>

            <?php

        } else {


            ?>
            <tr>
                <td><i class="bi bi-file"></i> <?= $file->getBasename() ?> </td>
                <td>
                    <div class="btn-group">
                        <?php if(str_starts_with(mime_content_type($file->getRealPath()), 'text/')) { ?><a href="<?= $this->getAbsoluteUrl('admin/files/edit/') ?>?file=<?=
                        base64_encode($file->getRealPath()) ?>" class="btn btn-sm btn-primary"><i class="bi bi-code"></i> <?= __('Edit') ?></a><?php } ?>

                        <a href="<?= $this->getAbsoluteUrl('admin/files/rename/') ?>?file=<?=
                        base64_encode($file->getRealPath()) ?>" class="btn btn-sm btn-primary"><i class="bi bi-chat"></i> <?= __('Rename') ?></a>


                        <a target="_blank" href="<?= $this->getAbsoluteUrl($current_web_path . $file->getBasename()) ?>" class="btn btn-sm btn-secondary"><i class="bi bi-globe"></i> <?= __
                            ('View') ?></a>
                        <a href="<?= $this->getAbsoluteUrl('admin/files/delete/') ?>?file=<?= base64_encode($file->getRealPath()) ?>" class="btn btn-sm btn-danger"
                           data-confirm="<?= __('Do you really want to delete file  %s?',
                            $file->getBasename()) ?>"><i class="bi bi-file-minus"></i> <?= __('Delete') ?></a>
                    </div>
                </td>

            </tr>

            <?php
        }

    }
    ?>


    </tbody>

    </table>
    </div>

<h2 class="my-md-5 h4"><?= __('Upload a file to the current folder') ?></h2>
<form method="post" enctype="multipart/form-data" action="<?= $this->getCurrentUrl() ?>">
    <input type="hidden" name="folder" value="<?= base64_encode($folder_real_path) ?>" />
    <div class="input-group">
        <input name="file" type="file" class="form-control" id="_file" aria-describedby="_file" aria-label="<?= __('Upload a file') ?>">
        <button class="btn btn-primary" type="submit" id="_file"><?= __('Upload') ?></button>
    </div>
</form>