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
use basteyy\Webstatt\Controller\Traits\InvitationsTrait;
use basteyy\Webstatt\Enums\InvitationType;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
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
use function basteyy\VariousPhpSnippets\getRandomString;

class RemoveInvitationLinkController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    use InvitationsTrait;

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $secret_key): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        $invitation = $this->isValidSecretKey($secret_key);

        if (!$invitation) {
            return $this->handleInvalidInvitation();
        }

        $this->getInvitationModel()->delete($invitation);

        FlashMessages::addSuccessMessage(__('Invitation #%s was deleted successfully', $invitation->getId()));

        return $this->adminRedirect('users/invite');
    }
}