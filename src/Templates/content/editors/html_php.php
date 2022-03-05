

<textarea id="_body" name="body" class="form-text"><?= $page->getBody() ?></textarea>


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
