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

class ViewContentVersionController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, string $content_page_secret, string $version_file_name): ResponseInterface
    {
        $page = ($this->getContentPagesDatabase())->findOneBy(['secret', '=', $content_page_secret]);

        if (!$page) {
            FlashMessages::addErrorMessage(__('Page not found.'));
            return $this->redirect('/admin/content');
        }

        $page = new PageAbstraction($page, $this->getConfigService());

        foreach($page->getAllVersions() as $timestamp => $path) {
            if(basename($path) === $version_file_name) {
                $version_content = file_get_contents($path);
            }
        }

        if(!isset($version_content)) {
            FlashMessages::addErrorMessage(__('Version %s of the page not found.', $version_file_name));
            return $this->redirect('/admin/content/edit/' . $content_page_secret);
        }


        return $this->render('content/version', [
            'page' => $page,
            'version_body' => $version_content
        ]);
    }
}