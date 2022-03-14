<?php

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use function basteyy\VariousPhpSnippets\__;

/** @var \basteyy\Webstatt\Models\Entities\PageEntity $page */
?>

<div class="col-12">

    <label for="_versions" class="form-label"><?= __('Versions') ?></label>
    <p class="text-xs mt-2 alert alert-danger">
        <?= __('You can restore former versions of the file. Be aware, that this will lead to a lost of the unsaved changes you may have did in the body right now.') ?>
    </p>

    <select class="form-select" id="_versions" name="version">
        <option selected></option>
        <optgroup label="<?= __('Please select') ?>">
            <?php
            foreach ($page->getStorage()->getAllVersions() as $version) {

                $file = new SplFileInfo($version);

                printf('<option value="%1$s">%2$s</option>', basename($file->getBasename(), '.' . $file->getExtension()), __('Version from %s', date('d M Y H:i:s', filemtime($version))));
            }
            ?>
        </optgroup>
    </select>

    <p class="text-end mt-3">
        <a href="#" class="btn btn-secondary btn-sm" id="_preview_version"><?= __('Preview selected version') ?></a>
        <a href="#" data-confirm="<?= __('Restore selected version and discard current changes?') ?>" class="btn btn-danger btn-sm" id="_restore_version"><?= __('Restore') ?></a>
    </p>

    <script>
        let selected_version = document.querySelector('select#_versions');
        document.querySelector('#_preview_version').addEventListener('click', function() {
            if('' !== selected_version.value ) {
                window.open('<?= $this->getCurrentUrl() ?>/version/' + selected_version.value, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=800,height=800" );
            }
        });
        document.querySelector('#_restore_version').addEventListener('click', function() {
            if('' !== selected_version.value ) {
                document.querySelector('#_restore_version').href = '<?= $this->getCurrentUrl() ?>/restore/' + selected_version.value;
            }
        });
    </script>

</div>