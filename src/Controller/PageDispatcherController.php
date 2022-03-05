<?php

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