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
        $page = ($this->getContentPagesDatabase())->findOneBy(['secret', '=', $content_page_secret]);

        if (!$page) {
            FlashMessages::addErrorMessage(__('Page not found.'));
            return $this->redirect('/admin/pages');
        }


        if ($this->isPost()) {


            // Change Content Type?
            if(PageType::tryFrom($request->getParsedBody()['contentType']) !== PageType::tryFrom($page['contentType'])) {
                if($this->getCurrentUser()->getRole() !== UserRole::SUPER_ADMIN) {
                    FlashMessages::addErrorMessage(__('You are not allowed to change the content type of a document.'));
                    return $this->redirect('/admin/content/edit/' . $content_page_secret);
                }

                $patched_page = new PageAbstraction($page, $this->getConfigService());
                $patched_page->changeContentTypeTo(PageType::tryFrom($request->getParsedBody()['contentType']) === PageType::MARKDOWN ? PageType::HTML_PHP : PageType::MARKDOWN);
            }

            $patched_page = new PageAbstraction(array_merge($page, $request->getParsedBody()), $this->getConfigService());

            $patched_page->updateBody($request->getParsedBody()['body']);

            $this->getContentPagesDatabase()->updateById($patched_page->getId(), $patched_page->toArray());

            FlashMessages::addSuccessMessage(__('Changes are saved'));

            return $this->redirect('/admin/content/edit/' . $content_page_secret);

        }

        return $this->render('Webstatt::pages/edit', [
            'page' => new PageAbstraction($page, $this->getConfigService())
        ]);
    }
}