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

namespace basteyy\Webstatt\Controller\UserInvitations;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Controller\Traits\AccountTrait;
use basteyy\Webstatt\Controller\Traits\InvitationsTrait;
use basteyy\Webstatt\Enums\InvitationType;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use basteyy\Webstatt\Models\Entities\UserEntity;
use basteyy\Webstatt\Models\InvitationsModel;
use DateTime;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getDateTimeFormat;
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\getRequestIpAddress;
use function basteyy\VariousPhpSnippets\varDebug;

class AcceptInvitationLinkController extends Controller
{
    protected UserRole $exact_user_role = UserRole::ANONYMOUS;

    use InvitationsTrait;

    use AccountTrait;

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $public_key): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        $invitation = $this->isValidPublicKey($public_key);

        if (!$invitation) {
            FlashMessages::addErrorMessage(__('The invitation was invalid. Try again'));
            return $this->redirect('/admin');
        }

        if (!$invitation->getActive()) {
            FlashMessages::addErrorMessage(__('Invitation not activated'));
            return $this->redirect('/admin');
        }


        if ($invitation->getAcceptanceLimitLeft() < 1) {
            FlashMessages::addErrorMessage(__('The invitation reached there limit'));
            return $this->redirect('/admin');
        }



        if ($this->isPost()) {
            // Create a new account
            if ($this->isValidNewAccountData($request->getParsedBody())) {

                // The User Data

                $salt = getRandomString(rand(18, 28));

                $userData = [
                    'email'    => $request->getParsedBody()['email'][0],
                    'signupIp' => getRequestIpAddress(),
                    'created'  => getDateTimeFormat(),
                    'role'     => $invitation->getRole(),
                    'password' => UserPasswordStrategy::getHash($request->getParsedBody()['password'][0], $salt),
                    'salt'     => $salt,
                    'secret'   => getRandomString(24)
                ];

                // Get role from invitation


                if (strlen($invitation->getAcceptanceRules()) > 0) {
                    $rules = explode(PHP_EOL, $invitation->getAcceptanceRules());

                    $valid = false;

                    foreach ($rules as $rule) {

                        $rule = rtrim($rule);

                        if ($rule[0] === $rule[strlen($rule) - 1] && $rule[0] === '*' && str_contains($userData['email'], substr($rule, 1, -1))) {
                            // Start und End as placeholder
                            $valid = true;
                        } elseif ($rule[0] === '*' && str_ends_with($userData['email'], substr($rule, 1))) {
                            // Placeholder at the beginning
                            $valid = true;
                        } elseif ($rule[strlen($rule) - 1] === '*' && str_ends_with($userData['email'], substr($rule, 0, -1))) {
                            // Placeholder at the end
                            $valid = true;
                        } elseif ($rule === $userData['email']) {
                            // No Placeholder!
                            $valid = true;
                        }
                    }

                    if (!$valid) {
                        FlashMessages::addErrorMessage(__('The data you used are not allowed'));
                        return $this->redirect(null, 302, $response);
                    }
                }

                // Data is valid, create user
                $user = $this->getUsersModel()->create($userData);

                FlashMessages::addSuccessMessage(__('You created a new account with your e-mail address %s. Login with your selected password.', $userData['email']));

                // Taker on of the counter
                $this->increaseUsedTimes($invitation);
                $this->addInvitedUserId($invitation, $user);

                return $this->redirect('/admin', 302, $response);


            }
        }

        return $this->adminRender('invitations/accept_link');
    }
}