<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Users;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\getRandomString;

class AddUserController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if ($this->isPost()) {
            $email = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['password'];
            $role = $request->getParsedBody()['role'];
            $db = $this->getUserDatabase();

            if(null === UserRole::tryFrom($role) ) {

                FlashMessages::addErrorMessage(sprintf('Ungültige Benutzerrolle %s', $role));

            }  elseif(strlen($password) < 8) {

                FlashMessages::addErrorMessage(sprintf('Passwort muss mindestens %s Zeichen enthalten', 8));

            } elseif(null === $db->findOneBy(['email', '=', $email])) {

                $salt = getRandomString(16);
                $db->insert([
                    'email'    => $email,
                    'password' => UserPasswordStrategy::getHash($password, $salt),
                    'salt'     => $salt,
                    'role'     => $role,
                    'secret' => getRandomString(24)
                ]);

                return $this->redirect('/admin/users#user_id_' . $db->getLastInsertedId() );
            } else {
                FlashMessages::addErrorMessage('Dieser Nutzer kann nicht hinzugefügt werden.');
            }

            return $this->redirect('/admin/users/add');
        }

        return $this->render('users/add');
    }
}