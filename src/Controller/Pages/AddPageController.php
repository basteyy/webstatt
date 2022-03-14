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
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\slugify;
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
            $data = [
                'url'          => '/' . slugify($request->getParsedBody()['url']),
                'name'         => $request->getParsedBody()['name'],
                'title'        => $request->getParsedBody()['title'],
                'description'  => $request->getParsedBody()['description'],
                'keywords'     => $request->getParsedBody()['keywords'],
                'pageType'     => $request->getParsedBody()['PageType'],
                'layout'       => $request->getParsedBody()['layout'],
                'online'    => (bool)$request->getParsedBody()['online'],
                'startpage' => (bool)$request->getParsedBody()['startpage'],
                'secret'       => getRandomString(18)
                //'body'        => $request->getParsedBody()['body'],
            ];

            if($data['url'] === '/n-a') {
                $data['url'] = '/';
            }

            if (in_array($data['url'], ['', '/']) && !isset($request->getParsedBody()['startpage'])) {
                FlashMessages::addErrorMessage(__('Invalid URL %s.', $data['url']));
            } else {

                /* Add the / to the url */
                if (!str_starts_with($data['url'], '/')) {
                    $data['url'] = '/' . $data['url'];
                }

                /** @var PagesModel $pages */
                $page = $this->getPagesModel()->create($data);

                FlashMessages::addSuccessMessage(__('New page created'));

            }

            return $this->redirect('/admin/pages/edit/' . $page->getSecret());

        }

        return $this->render('Webstatt::pages/add');
    }
}