<?php

use function basteyy\VariousPhpSnippets\__;

?>
<div class="col-12">
    <label class="form-label" for="_layout">
        <?= __('Choose a layout') ?>
    </label>
    <select id="_layout" name="layout" class="form-control">
        <option value="NONE"><?= __('No layout') ?></option>
        <?php
        foreach ($this->getLayouts() as $layout) {
            printf('<option value="%1$s">%1$s</option>', $layout);
        } ?>
    </select>
    <p class="text-xs mt-2 alert alert-info">
        <?= __('In case you have created one or more layouts, you can select which should be used in the content page. A layout is not required.') ?>
    </p>
</div>