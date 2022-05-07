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

namespace basteyy\Webstatt\Controller\Users;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\DisplayThemesEnum;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\MailHelper;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;

class AddUserController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if ($this->isPost()) {
            $email = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['password'];
            $role = $request->getParsedBody()['role'];
            $db = $this->getUsersModel();

            if (null === UserRole::tryFrom($role)) {

                FlashMessages::addErrorMessage(__('Invalid user role %s', $role));

            } elseif (strlen($password) < 8) {

                FlashMessages::addErrorMessage(__('Password needs at least %s signs', 8));

            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                FlashMessages::addErrorMessage(__('The mail address looks invalid'));

            } elseif (null !== $db->getRaw()->findOneBy(['email', '=', $email])) {

                FlashMessages::addErrorMessage(__('A user with the email address %s is already in the system', $email));

            } else {

                $salt = getRandomString(16);
                $db->getRaw()->insert([
                    'email'    => $email,
                    'password' => UserPasswordStrategy::getHash($password, $salt),
                    'salt'     => $salt,
                    'role'     => $role,
                    'secret'   => getRandomString(24),
                    'displayMode' => DisplayThemesEnum::LIGHT
                ]);

                /**Send a Welcome Mail ? */
                if(isset($request->getParsedBody()['send_welcome_mail'])) {

                    $mailhelper = new MailHelper($this->getConfigService());

                    if($mailhelper->isEnabled()) {
                        $mail = $mailhelper->newMail();
                        $mail->addAddress($email);
                        $mail->isHTML();
                        $mail->Subject = __('A new account was created for you');
                        $mail->Body = $this->getEngine()->render('Webstatt::mail/account_created', [
                            'user' => $this->getUsersModel()->findById($db->getRaw()->getLastInsertedId())
                        ]);
                        $mail->send();
                    }

                }

                return $this->redirect('/admin/users#user_id_' . $db->getRaw()->getLastInsertedId());
            }

            return $this->redirect('/admin/users/add');
        }

        return $this->render('Webstatt::users/add');
    }
}