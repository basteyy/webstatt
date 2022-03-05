<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Users;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class ListUsersController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::SUPER_ADMIN;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if ($this->isPost()) {
            $email = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['password'];

            $db = $this->getUserDatabase();


        }

        return $this->render('users/overview', [
            'users' => ($this->getUserDatabase())->findAll(),
            'primary_id' => ($this->getUserDatabase())->getPrimaryKey()
        ]);
    }
}