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
use basteyy\Webstatt\Enums\InvitationType;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Models\InvitationsModel;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\varDebug;

class AddInvatationLinkController extends Controller
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
                'acceptanceTimeoutDatetime' => $ttl
            ]);

            return $this->adminRedirect('/users/invite#' . $model->getRaw()->getLastInsertedId());

        }

        return $this->adminRender('users/add_invitation_link');

    }
}