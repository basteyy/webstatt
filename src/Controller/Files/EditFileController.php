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

namespace basteyy\Webstatt\Controller\Files;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserPasswordStrategy;
use basteyy\Webstatt\Helper\UserSession;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\IdNotAllowedException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\varDebug;

class EditFileController extends Controller
{
    public UserRole $minimum_user_role = UserRole::USER;


    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        /** @var $request Request */
        /** @var $response Response */

        $basic_folder = ROOT . DS . 'public' . DS;

        if (!isset($request->getQueryParams()['file'])) {
            FlashMessages::addErrorMessage(__('File required'));
            return $this->adminRedirect('/files');
        }

        $selected_file = base64_decode($request->getQueryParams()['file']);
        $selected_folder = dirname($selected_file) . DS;
        $file = new \SplFileInfo($selected_file);

        if(!file_exists($file->getRealPath())) {
            FlashMessages::addErrorMessage(__('Selected file %s not exists', $file->getBasename()));
            return $this->adminRedirect('files?folder='. base64_encode($selected_folder));
        }

        if (!str_starts_with($selected_file, $basic_folder)) {
            FlashMessages::addErrorMessage(__('Selected folder %s is out of allowed folder scope %s', $selected_folder, $basic_folder));
            return $this->adminRedirect('files');
        }

        if(!str_starts_with(mime_content_type($file->getRealPath()), 'text/')) {
            FlashMessages::addErrorMessage(__('Selected file %s is not supported to edit', $file->getBasename()));
            return $this->adminRedirect('files?folder='. base64_encode($selected_folder));
        }

        if($this->isPost()) {
            // Update File!

            file_put_contents($file->getRealPath(), $request->getParsedBody()['body']);

            FlashMessages::addSuccessMessage(__('File %s was updated', $file->getBasename()));
            return $this->adminRedirect();
        }

        return $this->adminRender('files/editor/textfile', ['file' => $file]);

    }

}