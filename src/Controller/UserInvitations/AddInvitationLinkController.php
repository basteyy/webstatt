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
use basteyy\Webstatt\Enums\InvitationType;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\InvitationsModel;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;

class AddInvitationLinkController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if($this->isPost()) {
            // Do something
            /** @var InvitationsModel $model */
            $model = $this->getModel(InvitationsModel::class);

            if (null === UserRole::tryFrom($request->getParsedBody()['userRole'])) {
                FlashMessages::addErrorMessage(__('Invalid user role %s', $request->getParsedBody()['userRole']));
            } elseif((int)$request->getParsedBody()['acceptance_limit'] != -1 && (int)$request->getParsedBody()['acceptance_limit'] < 1) {
                FlashMessages::addErrorMessage(__('You need to allow at least one sign up or use -1 for no limit', $request->getParsedBody()['acceptance_limit']));
            } else {
                // Build DateTime
                if(strlen($request->getParsedBody()['acceptance_timeout_date']) > 0 ) {
                    // Some Input!
                    $time = strlen($request->getParsedBody()['acceptance_timeout_time']) > 0 ? $request->getParsedBody()['acceptance_timeout_time'] : '23:59';

                    $ttl = new DateTime( $request->getParsedBody()['acceptance_timeout_date'] . $time );

                } else {
                    $ttl = new DateTime('next year');
                }

                $model->create([
                    'invitationType' => InvitationType::LINK,
                    'active' => true,
                    'acceptanceRules' => $request->getParsedBody()['acceptance_rules'],
                    'acceptanceLimit' => $request->getParsedBody()['acceptance_limit'],
                    'userRole' => $request->getParsedBody()['userRole'],
                    'acceptanceTimeoutDatetime' => $ttl,
                    'secretKey' => getRandomString(rand(54,72)),
                    'publicKey' => chunk_split(getRandomString(rand(54,72)), 8, '-')
                ]);

                return $this->adminRedirect('/users/invite#' . $model->getRaw()->getLastInsertedId());
            }

        }

        return $this->adminRender('invitations/add_invitation_link');

    }
}