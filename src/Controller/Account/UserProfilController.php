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

namespace basteyy\Webstatt\Controller\Account;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\UsersModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function basteyy\VariousPhpSnippets\__;

class UserProfilController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    protected array $loadModels = [
        UsersModel::class
    ];

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = $this->getCurrentUser();

        if ($this->isPost()) {

            if (strlen($request->getParsedBody()['alias']) < 4) {
                FlashMessages::addErrorMessage(__('Your alias needs at leas 4 signs. "%s" is to short', $request->getParsedBody()['alias']));
            } else {

                $this->getUsersModel()->patch($user, [
                    'alias' => $request->getParsedBody()['alias'],
                    'name'  => $request->getParsedBody()['name'],
                ]);

                FlashMessages::addSuccessMessage(__('Changes are saved'));

            }

            return $this->redirect('/admin/account');
        }

        return $this->render('Webstatt::profile/manage', [
            'user' => $user
        ]);
    }
}