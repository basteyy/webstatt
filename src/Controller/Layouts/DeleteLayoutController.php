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

namespace basteyy\Webstatt\Controller\Layouts;

use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Entities\LayoutEntity;
use basteyy\Webstatt\Models\LayoutsModel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function basteyy\VariousPhpSnippets\__;

class DeleteLayoutController extends \basteyy\Webstatt\Controller\Controller
{
    public UserRole $minimum_user_role = UserRole::USER;

    protected array $loadModels = [
        LayoutsModel::class
    ];

    protected LayoutsModel $LayoutsModel;

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        string                 $secret_key,
        string                 $id
    ): ResponseInterface
    {
        /** @var LayoutEntity $layout */
        $layout = $this->LayoutsModel->findById((int)$id);

        if ($layout->getSecret() !== $secret_key) {
            FlashMessages::addErrorMessage(__('Layout not found'));
            return $this->adminRedirect('layouts');
        }

        if ($this->isPost()) {
            $this->LayoutsModel->delete($layout);
            FlashMessages::addSuccessMessage(__('Layout %s was deleted', $layout->getName()));
            return $this->adminRedirect('layouts');
        }

        return $this->adminRender('layouts/delete', [
            'layout' => $layout
        ]);
    }

}