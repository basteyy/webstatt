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
use basteyy\Webstatt\Enums\DisplayThemesEnum;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\UsersModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function basteyy\VariousPhpSnippets\__;

class AccountSettingsController extends Controller
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

            $update_data = [];

            /** Display Mode Changes? */
            $update_data += [
                'displayMode' => isset($request->getParsedBody()['display_mode']) ? DisplayThemesEnum::DARK : DisplayThemesEnum::LIGHT
            ];

            /** Codemirror theme */
            $update_data += [
                'codemirror_theme' => isset($request->getParsedBody()['codemirror_theme']) ? $request->getParsedBody()['codemirror_theme'] : 'nord'
            ];


            $this->getUsersModel()->patch($user, $update_data);
            FlashMessages::addSuccessMessage(__('Changes are saved'));


            return $this->reload();
        }

        return $this->render('Webstatt::account/settings', [
            'user' => $user
        ]);
    }
}