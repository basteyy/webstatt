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
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;

class UserProfilController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $this->getCurrentUserData();

        if ($this->isPost()) {


            $this->getUserDatabase()->updateById($user->getId(), [
                'alias' => $request->getParsedBody()['alias'],
                'name'  => $request->getParsedBody()['name'],
            ]);
            FlashMessages::addSuccessMessage('Deine Angaben wurden gespeichert.');


            return $this->redirect('/admin/me');
        }

        return $this->render('Webstatt::profile/manage', [
            'user' => $user
        ]);
    }
}