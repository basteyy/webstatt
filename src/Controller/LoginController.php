<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller;

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use basteyy\Webstatt\Helper\UserSession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\getRandomString;

class LoginController extends Controller
{
    public UserRole $exact_user_role = UserRole::ANONYMOUS;

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

            $db = $this->getUserDatabase();

            $login = false;
            $user_id = null;

            if ($db->count() === 0 && $email === 'sebastian@eiweleit.de') {

                $salt = getRandomString(32);

                FlashMessages::addSuccessMessage('Account hinzugefügt.');

                $db->insert([
                    'email'    => $email,
                    'password' => UserPasswordStrategy::getHash($password, $salt),
                    'salt'     => $salt,
                    'role'     => UserRole::SUPER_ADMIN,
                    'secret' => getRandomString(12),
                    'name' => '',
                    'alias' => '',
                ]);

                $user_id = $db->getLastInsertedId();

                $login = true;
            } else {
                $user = $db->findOneBy(["email", "=", $email]);

                if ($user) {
                    if (UserPasswordStrategy::comparePassword($user['password'], $password, $user['salt'])) {
                        $user_id = $user[$db->getPrimaryKey()];
                        $login = true;
                    } else {
                        FlashMessages::addErrorMessage('Passwort falsch');
                    }
                } else {
                    FlashMessages::addErrorMessage('Ich erkenne dich nicht.');
                }

            }

            if ($login && isset($user_id)) {
                UserSession::startUserSession((int)$user_id);
                FlashMessages::addSuccessMessage('Du hast dich erfolgreich einloggen können.');
                return $this->redirect('/admin/dashboard');
            } else {
                return $this->redirect('/admin');
            }

        }

        return $this->render('Webstatt::login');
    }
}
