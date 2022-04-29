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
use function basteyy\VariousPhpSnippets\varDebug;

class EditInvitationLinkController extends Controller
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

        $invitation = $this->isValidSecretKey($secret_key, true);

        if (!$invitation) {
            return $this->handleInvalidInvitation();
        }

        if($this->isPost()) {

            // Build DateTime
            if(strlen($request->getParsedBody()['acceptance_timeout_date']) > 0 ) {
                // Some Input!
                $time = strlen($request->getParsedBody()['acceptance_timeout_time']) > 0 ? $request->getParsedBody()['acceptance_timeout_time'] : '23:59';

                $ttl = new DateTime( $request->getParsedBody()['acceptance_timeout_date'] . $time );

            } else {
                $ttl = new DateTime('next year');
            }

            $this->getInvitationModel()->patch($invitation, [
                'publicKey' => isset($request->getParsedBody()['new_public_key']) ? chunk_split(getRandomString(rand(54,72)), 8, '-') : $invitation->getPublicKey(),
                'active' => $request->getParsedBody()['active'] ?? false,
                'acceptanceRules' => $request->getParsedBody()['acceptance_rules'],
                'acceptanceLimit' => $request->getParsedBody()['acceptance_limit'],
                'acceptanceTimeoutDatetime' => $ttl,
            ]);

            FlashMessages::addSuccessMessage(__('Data updated'));

            return $this->adminRedirect();

        }

        return $this->adminRender('invitations/edit', [
            'invitationEntity' => $invitation
        ]);
    }
}