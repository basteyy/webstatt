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
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;

class EditUserController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $user_secret): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        $user = $this->getUsersModel()->findBySecret($user_secret);

        if ($user->getId() === $this->getCurrentUser()->getId()) {
            FlashMessages::addErrorMessage(__('You  cannot edit your own account'));
            return $this->redirect('/admin/users');
        }

        if ($this->isPost()) {

            $email = $request->getParsedBody()['email'];
            $role = $request->getParsedBody()['role'];

            if (null === UserRole::tryFrom($role)) {
                FlashMessages::addErrorMessage(sprintf('Invalid user role %s', $role));


            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                FlashMessages::addErrorMessage(__('The mail address looks invalid'));

            } elseif ($email !== $user->getEmail() && null !== $this->getUsersModel()->getRaw()->findOneBy([
                    ['email', '=', $email], 'AND', ['email', '!=', $user->getEmail()]
                ])) {

                FlashMessages::addErrorMessage(__('A user with the email address %s is already in the system', $email));

            } else {


                $this->getUsersModel()->patch($user, [
                    'email' => $email,
                    'role' => $role,
                    'alias' => $request->getParsedBody()['alias'],
                    'name'  => $request->getParsedBody()['name'],

                ]);

                FlashMessages::addSuccessMessage(__('Changes saved'));
            }


            return $this->adminRedirect('users/edit/' . $user_secret);
        }

        #$this->getUsersModel()->getRaw()->deleteById($user->getId());

        #FlashMessages::addSuccessMessage(__('User %s (%s) was deleted successfully', $user->getAnyName(), $user->getEmail()));


        return $this->adminRender('users/edit', [
            'user' => $user
        ]);
    }
}