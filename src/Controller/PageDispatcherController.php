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

namespace basteyy\Webstatt\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class PageDispatcherController extends Controller {
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */



        return $this->render('home');
    }
}