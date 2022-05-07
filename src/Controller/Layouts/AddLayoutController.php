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
use basteyy\Webstatt\Models\LayoutsModel;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Request;
use function basteyy\VariousPhpSnippets\__;

class AddLayoutController extends \basteyy\Webstatt\Controller\Controller
{
    public UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws \SleekDB\Exceptions\IOException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws \SleekDB\Exceptions\InvalidArgumentException
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {
        /** @var Request $request */

        if ($this->isPost()) {

            $data = [];
            $errors = [];
            $model = $this->getModel(LayoutsModel::class);

            if(strlen($request->getParsedBody()['name']) < 4 ) {
                $errors[] = __('Name must contain at least 4 signs');
            }

            /** Duplicate? */
            if($model->findByName($request->getParsedBody()['name']) !== null) {
                $errors[] = __('Name already taken');
            }

            if (count($errors) === 0) {

                $data = [
                    'name' => $request->getParsedBody()['name'],
                    'activated' => isset($request->getParsedBody()['activated'])
                ];

                $model->create($data);

                FlashMessages::addSuccessMessage(__('Layout %s created', $model->getLastId()));

                return $this->adminRedirect('layouts#' . $model->getLastId());
            }

            FlashMessages::addErrorMessage($errors);

            return $this->reload();
        }

        return $this->adminRender('layouts/add', [
            'layouts' => []
        ]);
    }

}