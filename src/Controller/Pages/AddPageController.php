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

namespace basteyy\Webstatt\Controller\Pages;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use basteyy\Webstatt\Models\Entities\PageEntity;
use basteyy\Webstatt\Models\PagesModel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class AddPageController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($this->isPost()) {

            /* Inspect the new URL */
            $url = $request->getParsedBody()['url'];

            if(in_array($url, ['', '/']) && !isset($request->getParsedBody()['is_startpage'])) {
                FlashMessages::addErrorMessage(__('Invalid URL %s.', $url));
            } else {

                /** @var PagesModel $pages */
                $pages = $this->loadModel(PagesModel::class);

                /** @var PageEntity $test */
                $test = $pages->create($request->getParsedBody());

                varDebug($pages->save($test));

                #$this->getContentPagesDatabase()->insert($content->toArray());

                FlashMessages::addSuccessMessage(__('New page created'));

            }




            return $this->redirect('/admin/pages/edit/' .  $content->getSecret());

        }

        return $this->render('Webstatt::pages/add');
    }
}