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

namespace basteyy\Webstatt\Controller\Account;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\DisplayThemesEnum;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\MailHelper;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use basteyy\Webstatt\Helper\UserSession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getDateTimeFormat;
use function basteyy\VariousPhpSnippets\getRandomString;

class LoginController extends Controller
{
    public UserRole $exact_user_role = UserRole::ANONYMOUS;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException|InvalidConfigurationException
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if ($this->isPost()) {
            $email = $request->getParsedBody()['email'];
            $password = $request->getParsedBody()['password'];

            $db = $this->getUsersModel();

            $login = false;
            $user_id = null;

            if ($db->getRaw()->count() === 0 && $email === 'sebastian@eiweleit.de') {

                $salt = getRandomString(32);

                FlashMessages::addSuccessMessage(__('Your account was created. You are a super admin.'));


                $db->getRaw()->insert([
                    'email'    => $email,
                    'password' => UserPasswordStrategy::getHash($password, $salt),
                    'salt'     => $salt,
                    'role'     => UserRole::SUPER_ADMIN,
                    'secret' => getRandomString(12),
                    'name' => '',
                    'alias' => '',
                    'created' => getDateTimeFormat(),
                    'lastlogin' => getDateTimeFormat(),
                    'displayMode' => DisplayThemesEnum::LIGHT
                ]);

                $user_id = $db->getRaw()->getLastInsertedId();

                /**Send welcome mail, if mail is marked as working */
                if($this->getConfigService()->getMailConfig()['activate_mail_system']) {

                    $mailhelper = new MailHelper($this->getConfigService());

                    if($mailhelper->isEnabled()) {
                        $mail = $mailhelper->newMail();
                        $mail->addAddress($email);
                        $mail->Subject = 'Welcome to a new Webstatt Installation';
                        $mail->Body = $this->getEngine()->render('Webstatt::mail/account_created', [
                            'user' => $this->getUsersModel()->findById($user_id)
                        ]);
                        $mail->send();
                    }

                }

                $login = true;
            } else {
                $user = $db->getRaw()->findOneBy(["email", "=", $email]);

                if ($user) {
                    if (UserPasswordStrategy::comparePassword($user['password'], $password, $user['salt'])) {
                        $user_id = $user[$db->getRaw()->getPrimaryKey()];
                        $login = true;

                        $update_data = ['lastlogin' => getDateTimeFormat()];

                        if(!isset($user['displayMode'])) {
                            $update_data['displayMode'] = DisplayThemesEnum::LIGHT;
                        }

                        $db->getRaw()->updateById($user_id, $update_data);

                    } else {
                        FlashMessages::addErrorMessage(__('Password was incorrect'));
                    }
                } else {
                    FlashMessages::addErrorMessage(__('Unknown/wrong user'));
                }

            }

            if ($login && isset($user_id)) {
                UserSession::startUserSession((int)$user_id);
                FlashMessages::addSuccessMessage(__('Welcome back, you logged in successfully'));
                return $this->redirect('/admin/dashboard');
            } else {
                return $this->redirect('/admin');
            }

        }

        return $this->render('Webstatt::login');
    }
}
