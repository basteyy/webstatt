<?php
/**
 * Webstatt
 *
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://webstatt.org
 * @website https://github.com/basteyy/webstatt
 * @license CC BY-SA 4.0
 */

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Snippets;

use basteyy\Webstatt\Controller\Controller;
use Psr\Http\Message\ResponseInterface;

class ListSnippetsController extends Controller {

    public function __invoke() : ResponseInterface {



        return $this->adminRender('snippets/list', [
            'snippets' => []
        ]);
    }

}