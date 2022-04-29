<?php

use function basteyy\VariousPhpSnippets\__;

$this->layout('Webstatt::layouts/extern', [
    'title' => strlen($terms) > 1 ? strip_tags(substr($terms, 0, strpos($terms, PHP_EOL))) : __('Terms')]);

echo $terms;
