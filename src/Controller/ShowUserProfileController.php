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

namespace basteyy\Webstatt\Controller;

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
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\varDebug;

class ShowUserProfileController extends Controller
{
    public UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $user_secret): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */
        return $this->render('Webstatt::user_profile', [
            'user' => $this->getUsersModel()->findBySecret($user_secret)
        ]);
    }
}
