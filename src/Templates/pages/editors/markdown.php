<?php
/** @var \basteyy\Webstatt\Models\Abstractions\PageAbstraction $page */
?>

<textarea id="_body" name="body" class="form-text"><?= $page->getBody() ?></textarea>

<link rel="stylesheet" href="<?= $this->cacheLocal('https://unpkg.com/easymde/dist/easymde.min.css') ?>">
<script src="<?= $this->cacheLocal('https://unpkg.com/easymde/dist/easymde.min.js') ?>"></script>
<script>
    var easyMDE = new EasyMDE({element: document.getElementById("_body"), spellChecker: false});
</script>