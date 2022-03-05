<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller;

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserSession;
use JetBrains\PhpStorm\NoReturn;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\varDebug;

class LogoutController extends Controller
{
    public UserRole $minimum_user_role = UserRole::USER;

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
     public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */
        UserSession::endUserSession();


        FlashMessages::addSuccessMessage('Du hast die Sitzung erfolgreich beendet.');

        return $this->redirect('/admin');
    }
}