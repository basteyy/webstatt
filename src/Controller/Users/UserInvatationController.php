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
use basteyy\Webstatt\Models\InvitationsModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;

class UserInvatationController extends Controller
{
    protected UserRole $exact_user_role = UserRole::SUPER_ADMIN;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        if($this->isPost()) {
            // Do something
        }

        return $this->adminRender('users/invite', [

            'pending_invitation_links' => $this->getModel(InvitationsModel::class)->getActiveInvitationLinks()


        ]);

    }
}