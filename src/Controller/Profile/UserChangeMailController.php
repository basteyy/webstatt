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

namespace basteyy\Webstatt\Controller\Profile;

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

class UserChangeMailController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $this->getCurrentUserData();

        if(!$user) {
            throw new \Exception('You are not logged in.');
        }


        if($this->isPost()) {

            $mail = $user->getEmail() !== $request->getParsedBody()['email'] && $request->getParsedBody()['email'] === $request->getParsedBody()['email_confirm'] && filter_var
            ($request->getParsedBody()['email_confirm'],
                    FILTER_VALIDATE_EMAIL) ? $request->getParsedBody()['email_confirm'] : false;

            if(!UserPasswordStrategy::comparePassword($user->getPassword(), $request->getParsedBody()['password'], $user->getSalt())) {
                FlashMessages::addErrorMessage('Passwort war nicht korrekt.');
            } elseif(!$mail) {
                FlashMessages::addErrorMessage('E-Mailadresse nicht gültig oder nicht korrekt bestätigt.');
            } elseif(($this->getUserDatabase())->findOneBy([
                    ['email', '=', $mail], 'AND', ['email', '!=', $user->getEmail()]
                ])) {
                FlashMessages::addErrorMessage('E-Mailadresse bereits vergeben.');

            } else {
                $this->getUserDatabase()->updateById($user->getId(), [
                    'email' => $mail
                ]);
                FlashMessages::addSuccessMessage('Deine E-Mailadresse wurde geändert.');
            }


            return $this->redirect('/admin/me/email');
        }

        return $this->render('Webstatt::profile/change_mail', [
            'user' => $user
        ]);
    }
}