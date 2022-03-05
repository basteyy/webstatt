<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Content;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\ContentType;
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

class EditContentController extends Controller
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
            return $this->redirect('/admin/content');
        }


        if ($this->isPost()) {


            $patched_page = new PageAbstraction(array_merge($page, $request->getParsedBody()), $this->getConfigService());

            $patched_page->updateBody($request->getParsedBody()['body']);


            $this->getContentPagesDatabase()->updateById($patched_page->getId(), $patched_page->toArray());

            FlashMessages::addSuccessMessage(__('Changes are saved'));

            return $this->redirect('/admin/content/edit/' . $content_page_secret);

        }

        return $this->render('Webstatt::content/edit', [
            'page' => new PageAbstraction($page, $this->getConfigService())
        ]);
    }
}