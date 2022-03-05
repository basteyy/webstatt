<?php
/** @var PageAbstraction $page */

use basteyy\Webstatt\Models\Abstractions\PageAbstraction;

if($page->getLayout() !== null) {
    $this->layout($page->getLayout(), ['page' => $page]);
}

?>

<?= $page->getBody() ?>
