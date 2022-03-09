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
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;

class ChangeAccountPasswordController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($this->isPost()) {

            if (!UserPasswordStrategy::comparePassword($this->getCurrentUser()->getPassword(), $request->getParsedBody()['password'], $this->getCurrentUser()->getSalt())) {

                FlashMessages::addErrorMessage(__('Password was incorrect'));

            } elseif (strlen($request->getParsedBody()['password_new']) < 8) {

                FlashMessages::addErrorMessage(__('Fill in a password with at least 8 signs'));

            } elseif ($request->getParsedBody()['password'] === $request->getParsedBody()['password_new']) {

                FlashMessages::addErrorMessage(__('You need to fill in a new password (not your current password'));

            } elseif ($request->getParsedBody()['password_new_confirm'] !== $request->getParsedBody()['password_new']) {

                FlashMessages::addErrorMessage(__('You need to confirm the new password'));

            } else {

                $salt = getRandomString(32);

                $this->getUsersModel()->patch($this->getCurrentUser(), [
                    'password' => UserPasswordStrategy::getHash($request->getParsedBody()['password_new'], $salt),
                    'salt'     => $salt,
                    'secret'   => getRandomString(12)
                ]);

                FlashMessages::addSuccessMessage(__('Changes saved'));

            }


            return $this->adminRedirect('account/password');
        }

        return $this->render('Webstatt::account/change_password', [
        ]);
    }
}