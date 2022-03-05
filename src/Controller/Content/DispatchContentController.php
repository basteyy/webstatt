<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Content;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Abstractions\PageAbstraction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class DispatchContentController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::ANONYMOUS;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $page = $this->getContentPagesDatabase()->findOneBy(['url', '=', substr($request->getUri()->getPath(), 1)]);

        if(!isset($page)) {
            FlashMessages::addErrorMessage(__('Page not found'));
            return $this->render_404();
        }

        return $this->render('Webstatt::content/dispatch', [
            'page' => new PageAbstraction($page)
        ]);
    }
}