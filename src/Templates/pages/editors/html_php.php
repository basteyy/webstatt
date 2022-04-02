<?php
/** @var \basteyy\Webstatt\Models\Entities\PageEntity $page */
?>

<textarea id="_body" name="body" class="form-text"><?= $page->getStorage()->getBody(false) ?></textarea>


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

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js') ?>" integrity="sha512-03Ei8A+mDhwF6O/CmXM47U4A9L7TobAxMbPV2Wn5cEbY76lngHQRyvvmnqhJ8IthfoxrRqmtoBxQCxOC7AOeKw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchtags.min.js') ?>" integrity="sha512-RhFcU90dzfpVCAfiAAFCqH/UESr9/ZzrwX9gW1ZjRh9kPu2CTqvWuk85U6ECWis/M9/yZemU+sheJspFobQOag==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js') ?>" integrity="sha512-UWevuuTs1/TWFZfIvCUtPfoiL4sIt14BmDftB8GAYEtT3DnYCwR1qqD9YQJw8ckcu/YV0zgE+IEIKsffcpYBGA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= $this->cacheLocal('https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js') ?>" integrity="sha512-UWfBe6aiZInvbBlm91IURVHHTwigTPtM3M4B73a8AykmxhDWq4EC/V2rgUNiLgmd/i0y0KWHolqmVQyJ35JsNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    (function(mod) {
        if (typeof exports == "object" && typeof module == "object") // CommonJS
            mod(require("codemirror/lib/codemirror"));
        else if (typeof define == "function" && define.amd) // AMD
            define(["codemirror/lib/codemirror"], mod);
        else // Plain browser env
            mod(CodeMirror);
    })(function(CodeMirror) {

        CodeMirror.extendMode("css", {
            commentStart: "/*",
            commentEnd: "*/",
            newlineAfterToken: function(_type, content) {
                return /^[;{}]$/.test(content);
            }
        });

        CodeMirror.extendMode("javascript", {
            commentStart: "/*",
            commentEnd: "*/",
            // FIXME semicolons inside of for
            newlineAfterToken: function(_type, content, textAfter, state) {
                if (this.jsonMode) {
                    return /^[\[,{]$/.test(content) || /^}/.test(textAfter);
                } else {
                    if (content == ";" && state.lexical && state.lexical.type == ")") return false;
                    return /^[;{}]$/.test(content) && !/^;/.test(textAfter);
                }
            }
        });

        var inlineElements = /^(a|abbr|acronym|area|base|bdo|big|br|button|caption|cite|code|col|colgroup|dd|del|dfn|em|frame|hr|iframe|img|input|ins|kbd|label|legend|link|map |object|optgroup|option|param|q|samp|script|select|small|span|strong|sub|sup|textarea|tt|h1|h2|h3|var)$/;

        CodeMirror.extendMode("xml", {
            commentStart: "<!--",
            commentEnd: "-->",
            newlineAfterToken: function(type, content, textAfter, state) {
                var inline = false;
                if (this.configuration == "html")
                    inline = state.context ? inlineElements.test(state.context.tagName) : false;
                return !inline && ((type == "tag" && />$/.test(content) && state.context) ||
                    /^</.test(textAfter));
            }
        });

        // Comment/uncomment the specified range
        CodeMirror.defineExtension("commentRange", function (isComment, from, to) {
            var cm = this, curMode = CodeMirror.innerMode(cm.getMode(), cm.getTokenAt(from).state).mode;
            cm.operation(function() {
                if (isComment) { // Comment range
                    cm.replaceRange(curMode.commentEnd, to);
                    cm.replaceRange(curMode.commentStart, from);
                    if (from.line == to.line && from.ch == to.ch) // An empty comment inserted - put cursor inside
                        cm.setCursor(from.line, from.ch + curMode.commentStart.length);
                } else { // Uncomment range
                    var selText = cm.getRange(from, to);
                    var startIndex = selText.indexOf(curMode.commentStart);
                    var endIndex = selText.lastIndexOf(curMode.commentEnd);
                    if (startIndex > -1 && endIndex > -1 && endIndex > startIndex) {
                        // Take string till comment start
                        selText = selText.substr(0, startIndex) +
                            // From comment start till comment end
                            selText.substring(startIndex + curMode.commentStart.length, endIndex) +
                            // From comment end till string end
                            selText.substr(endIndex + curMode.commentEnd.length);
                    }
                    cm.replaceRange(selText, from, to);
                }
            });
        });

        // Applies automatic mode-aware indentation to the specified range
        CodeMirror.defineExtension("autoIndentRange", function (from, to) {
            var cmInstance = this;
            this.operation(function () {
                for (var i = from.line; i <= to.line; i++) {
                    cmInstance.indentLine(i, "smart");
                }
            });
        });

        // Applies automatic formatting to the specified range
        CodeMirror.defineExtension("autoFormatRange", function (from, to) {
            var cm = this;
            var outer = cm.getMode(), text = cm.getRange(from, to).split("\n");
            var state = CodeMirror.copyState(outer, cm.getTokenAt(from).state);
            var tabSize = cm.getOption("tabSize");

            var out = "", lines = 0, atSol = from.ch === 0;
            function newline() {
                out += "\n";
                atSol = true;
                ++lines;
            }

            for (var i = 0; i < text.length; ++i) {
                var stream = new CodeMirror.StringStream(text[i], tabSize);
                while (!stream.eol()) {
                    var inner = CodeMirror.innerMode(outer, state);
                    var style = outer.token(stream, state), cur = stream.current();
                    stream.start = stream.pos;
                    if (!atSol || /\S/.test(cur)) {
                        out += cur;
                        atSol = false;
                    }
                    if (!atSol && inner.mode.newlineAfterToken &&
                        inner.mode.newlineAfterToken(style, cur, stream.string.slice(stream.pos) || text[i+1] || "", inner.state))
                        newline();
                }
                if (!stream.pos && outer.blankLine) outer.blankLine(state);
                if (!atSol && i < text.length - 1) newline();
            }

            cm.operation(function () {
                cm.replaceRange(out, from, to);
                for (var cur = from.line + 1, end = from.line + lines; cur <= end; ++cur)
                    cm.indentLine(cur, "smart");
                cm.setSelection(from, cm.getCursor(false));
            });
        });
    });

    let myCodeMirror = CodeMirror.fromTextArea(document.getElementById('_body'), {
        theme: 'nord',
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        matchTags: {bothTags: true},
        extraKeys: {"Ctrl-J": "toMatchingTag"}
    });

    function getSelectedRange() {
        return { from: myCodeMirror.getCursor(true), to: myCodeMirror.getCursor(false) };
    }

    function autoFormatSelection() {
        var range = getSelectedRange();
        myCodeMirror.autoFormatRange(range.from, range.to);
    }

    function commentSelection(isComment) {
        var range = getSelectedRange();
        myCodeMirror.commentRange(isComment, range.from, range.to);
    }
</script>

<style>
    .CodeMirror {
        border: 1px solid #eee;
        height: 75vh;
    }
</style>

<table>
    <tr>
        <td>
            <a href="javascript:autoFormatSelection()">
                Autoformat Selected
            </a>
        </td>
        <td>
            <a href="javascript:commentSelection(true)">
                Comment Selected
            </a>
        </td>
        <td>
            <a href="javascript:commentSelection(false)">
                Uncomment Selected
            </a>
        </td>
    </tr>
</table>