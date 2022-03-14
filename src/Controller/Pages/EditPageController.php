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
use basteyy\Webstatt\Enums\PageType;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\slugify;
use function basteyy\VariousPhpSnippets\varDebug;

class EditPageController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $content_page_secret): ResponseInterface
    {
        $page = $this->getPagesModel()->findOneBySecret($content_page_secret, false);

        if (!$page) {
            FlashMessages::addErrorMessage(__('Page not found.'));
            return $this->redirect('/admin/pages');
        }


        if ($this->isPost()) {

            /* Copy the parsed body to a new array and unset fields, which are not allowed to store in the database */
            $data = [
                'url'         => '/' . slugify($request->getParsedBody()['url']),
                'name'        => $request->getParsedBody()['name'],
                'title'       => $request->getParsedBody()['title'],
                'description' => $request->getParsedBody()['description'],
                'keywords'    => $request->getParsedBody()['keywords'],
                'layout'      => $request->getParsedBody()['layout'],
                'online'    => (bool)$request->getParsedBody()['online'],
                'startpage' => (bool)$request->getParsedBody()['startpage'],
                //'body'        => $request->getParsedBody()['body'],
                'pageType' => isset($request->getParsedBody()['PageType']) && $this->getCurrentUser()->isAdmin() ? PageType::tryFrom($request->getParsedBody()['PageType'])
                    : $page->getPageType()
            ];

            $body = $request->getParsedBody()['body'];

            if($data['startpage']) {
                /* Remove startpage from other startpage */
                $old_startpage = $this->getPagesModel()->getStartpage();
                if($old_startpage && $old_startpage->getId() !== $page->getId()) {
                    $this->getPagesModel()->patch($old_startpage, ['startpage' => false]);
                    FlashMessages::addErrorMessage(__('Former startpage %s (#%s) is not the startpage anymore', $old_startpage->getName(), $old_startpage->getId()));
                }
            }

            if (in_array($data['url'], ['', '/']) && !isset($request->getParsedBody()['startpage'])) {
                FlashMessages::addErrorMessage(__('Invalid URL %s.', $data['url']));
                return $this->redirect('/admin/pages/edit/' . $content_page_secret);
            }

            /* Different hash of the body? */
            if($page->getStorage()->getHash() !== hash(FAST_HASH, $body)) {
                $page->getStorage()->writeBody($body);
                FlashMessages::addSuccessMessage(__('Body was updated'));
            }

            /* Change Content Type */
            if($this->getCurrentUser()->isAdmin() && $data['pageType'] !== $page->getPageType()) {
                $page->getStorage()->changePageType($data['pageType']);
                FlashMessages::addSuccessMessage(__('Page Type was changed to %s', $data['pageType']->value));
            }

            /* Patch the entry in the database */
            $this->getPagesModel()->patch($page, $data);

            /* Success message */
            FlashMessages::addSuccessMessage(__('Changes are saved'));

            /* Flush Cache */
            if(APCU_SUPPORT) {
                apcu_clear_cache();
            }

            /* Redirect */
            return $this->redirect('/admin/pages/edit/' . $content_page_secret);


            /**
             *
             * // Change Content Type?
             * if(PageType::tryFrom($request->getParsedBody()['pageType']) !== PageType::tryFrom($page['pageType'])) {
             * if($this->getCurrentUser()->getRole() !== UserRole::SUPER_ADMIN) {
             * FlashMessages::addErrorMessage(__('You are not allowed to change the content type of a document.'));
             * return $this->redirect('/admin/content/edit/' . $content_page_secret);
             * }
             *
             * $patched_page = new PageAbstraction($page, $this->getConfigService());
             * $patched_page->changepageTypeTo(PageType::tryFrom($request->getParsedBody()['pageType']) === PageType::MARKDOWN ? PageType::HTML_PHP : PageType::MARKDOWN);
             * }
             *
             * $patched_page = new PageAbstraction(array_merge($page, $request->getParsedBody()), $this->getConfigService());
             *
             * $patched_page->updateBody($request->getParsedBody()['body']);
             *
             * $this->getContentPagesDatabase()->updateById($patched_page->getId(), $patched_page->toArray());
             **/
        }

        return $this->render('Webstatt::pages/edit', [
            'page' => $page
        ]);
    }
}