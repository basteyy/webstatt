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
use basteyy\Webstatt\Controller\Traits\FilebrowserTrait;
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

class RenameController extends Controller
{
    public UserRole $minimum_user_role = UserRole::USER;


    use FilebrowserTrait;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidArgumentException
     * @throws IdNotAllowedException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if (!isset($request->getQueryParams()['file']) && !isset($request->getQueryParams()['folder'])) {
            FlashMessages::addErrorMessage(__('File/folder required'));
            return $this->adminRedirect('/files');
        }

        if (isset($request->getQueryParams()['file']) && isset($request->getQueryParams()['folder'])) {
            FlashMessages::addErrorMessage(__('You can only rename a file or a folder'));
            return $this->adminRedirect('/files?folder=' . $request->getQueryParams()['folder']);
        }

        if(isset($request->getQueryParams()['file'])) {
            return $this->renameFile(new \SplFileInfo(base64_decode($request->getQueryParams()['file'])));
        } elseif ( $request->getQueryParams()['folder']) {
            return $this->renameFolder($request, $response, new \SplFileInfo(base64_decode($request->getQueryParams()['folder'])));
        } else {
            FlashMessages::addErrorMessage(__('Required data nit found!'));
            return $this->adminRedirect('/files');
        }

    }

    private function renameFolder(ServerRequestInterface $request, ResponseInterface $response, \SplFileInfo $folder) : ResponseInterface {

        if (!str_starts_with($folder->getRealPath(), $this->getBaseFolder())) {
            FlashMessages::addErrorMessage(__('Selected folder %s is out of allowed folder scope %s', $folder->getRealPath(), $this->getBaseFolder()));
            return $this->adminRedirect('files');
        }

        if($this->isPost()) {
            $name = $request->getParsedBody()['folder_name'];
            $absolute_folder_name = dirname($folder->getRealPath()) . DS . $name . DS;


            if (!str_starts_with($absolute_folder_name, $this->getBaseFolder())) {
                FlashMessages::addErrorMessage(__('Selected folder %s is out of allowed folder scope %s', $folder->getRealPath(), $this->getBaseFolder()));
            } elseif (!ctype_alnum($name)) {
                FlashMessages::addErrorMessage(__('New folder name contains invalid characters', $name));
            } elseif (is_dir($absolute_folder_name)) {
                FlashMessages::addErrorMessage(__('A folder with that name already exists (%s)', $absolute_folder_name));
            } else {
                rename($folder->getRealPath(), $absolute_folder_name);
                FlashMessages::addSuccessMessage(__('Folder %s renamed to %s (%s)', $folder->getBasename(), $name, $absolute_folder_name));
                return $this->adminRedirect('files/rename?folder=' . base64_encode($absolute_folder_name));
            }
            return $this->adminRedirect();
        }


        return $this->adminRender('files/rename/folder', ['file' => $folder]);
    }

}