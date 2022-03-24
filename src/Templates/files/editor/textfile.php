<?php

/** @var SplFileInfo $file */

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/acp', ['title' => __('Edit file %s overview', $file->getBasename())]);

?>


<h1 class="my-md-5">
    <?= __('Edit file %s', $file->getBasename()) ?>
</h1>
<div class="alert alert-info" role="alert">
    <p>
        <?= __('Be aware, that you edit the file %s (%s) direct and without any backup', $file->getBasename(), $file->getRealPath()) ?>
    </p>
</div>

<div class="my-3">
    <a class="btn btn-secondary" href="<?= $this->getAbsoluteUrl('/admin/files') ?>?folder=<?= base64_encode(dirname($file->getRealPath())) ?>"><?= __('Back to folder') ?></a>
</div>

<form method="post" action="<?= $this->getCurrentUrl() ?>">
    <textarea id="_body" name="body" class="form-text"><?= file_get_contents($file->getRealPath()) ?></textarea>
    <div class="text-end my-3">
        <input class="btn btn-primary" type="submit" value="<?=__('Save') ?>" />
    </div>
</form>


<link rel="stylesheet" href="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css') ?>"
      integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js') ?>"
        integrity="sha512-xwrAU5yhWwdTvvmMNheFn9IyuDbl/Kyghz2J3wQRDR8tyNmT8ZIYOd0V3iPYY/g4XdNPy0n/g0NvqGu9f0fPJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js') ?>"
        integrity="sha512-hVV7wKBA5Cy5BNo3JkDte8hAobbeXMF8ZTgmmVrshoxcBSSfXn3Z+sigvV6o7bbk6zHSEMWp8RxCbWyPIuPB6Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js') ?>"
        integrity="sha512-0IM15+FEzmvrcePHk/gDCLbZnmja9DhCDUrESXPYLM4r+eDtNadxDUa5Fd/tNQGCbCoxu75TaVuvJkdmq0uh/w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js') ?>"
        integrity="sha512-VoNvAZ5k1KyV+FeeKLhddu9NeFGFKgGVDyPs07F3BzEO9b9aMDwMTmOgGfmr0dGP6IR+3OH6o/47uMnGNV38WA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/nord.min.css') ?>"
      integrity="sha512-sPc4jmw78pt6HyMiyrEt3QgURcNRk091l3dZ9M309x4wM2QwnCI7bUtsLnnWXqwBMECE5YZTqV6qCDwmC2FMVA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>


<script>
    let myCodeMirror = CodeMirror.fromTextArea(document.getElementById('_body'), {
        theme: 'nord',
        lineNumbers: true,
        matchBrackets: true
    });
</script>

<style>
    .CodeMirror {
        border: 1px solid #eee;
        height: 75vh;
    }
</style>
