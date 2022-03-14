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
use SplFileInfo;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class RestorePageVersionController extends Controller
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
        $page = $this->getPagesModel()->findOneBySecret($content_page_secret, false);

        if (!$page) {
            FlashMessages::addErrorMessage(__('Page not found.'));
            return $this->redirect('/admin/pages');
        }

        foreach ($page->getStorage()->getAllVersions() as $timestamp => $path) {

            $file = new SplFileInfo($path);

            if ($file->getBasename('.' . $file->getExtension()) === $version_file_name) {
                $version_content = file_get_contents($path);
            }
        }

        if (!isset($version_content)) {
            FlashMessages::addErrorMessage(__('Version %s of the page not found.', $version_file_name));
            return $this->redirect('/admin/content/edit/' . $content_page_secret);
        }

        $page->getStorage()->writeBody($version_content);

        FlashMessages::addSuccessMessage(__('Version %s restored', $version_file_name));
        return $this->redirect('/admin/pages/edit/' . $content_page_secret);

    }
}