<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Controller\Content;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\ContentType;
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

class AddContentController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     * @throws InvalidConfigurationException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($this->isPost()) {

            $content = new PageAbstraction(
                $request->getParsedBody(),
                $this->getConfigService()
            );

            $this->getContentPagesDatabase()->insert($content->toArray());

            FlashMessages::addSuccessMessage(__('New content page created'));

            return $this->redirect('/admin/content/edit/' . $this->getContentPagesDatabase()->getLastInsertedId());

        }

        return $this->render('Webstatt::content/add');
    }
}