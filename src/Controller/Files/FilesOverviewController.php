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

class FilesOverviewController extends Controller
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

        if(isset($request->getQueryParams()['folder'])) {

            $selected_folder = realpath(base64_decode($request->getQueryParams()['folder']));

            if (!str_ends_with($selected_folder, DS)) {
                $selected_folder .= DS;
            }

            if(!str_starts_with($selected_folder, $basic_folder)) {
                FlashMessages::addErrorMessage(__('Selected folder %s is out of allowed folder scope %s', $selected_folder, $basic_folder));
                return $this->adminRedirect('files');
            }

            $folder = new \RecursiveDirectoryIterator($selected_folder);
            $current_folder = $selected_folder;

        } else {
            $folder = new \RecursiveDirectoryIterator($basic_folder);
            $current_folder = $basic_folder;
        }

        /* Upload a new file to the current folder */
        if($this->isPost()) {

            if ($request->getUploadedFiles()['file']->getError() !== UPLOAD_ERR_OK) {
                FlashMessages::addErrorMessage(__('Upload failed'));
                return $this->adminRedirect();
            }

            $request->getUploadedFiles()['file']->moveTo($current_folder . strtolower($request->getUploadedFiles()['file']->getClientFilename()));
            FlashMessages::addSuccessMessage(__('File %s was uploaded to %s', $request->getUploadedFiles()['file']->getClientFilename(), $current_folder));
            return $this->adminRedirect();

        }


        /* Folder up? */
        $upper_folder =  (isset($selected_folder) && $basic_folder !== $selected_folder)  ? dirname($selected_folder) : $basic_folder;

        $folder_helper = [];
        foreach($folder as $splFileInfo) {

            if(!in_array($splFileInfo->getBasename(), ['.', '..'])) {
                $folder_helper[] = $splFileInfo;
            }
        }



        return $this->render('Webstatt::files/overview', [
            'folder' => $folder_helper,
            'upper_folder' => $upper_folder,
            'folder_real_path' => $current_folder,
            'current_web_path' => DS . str_replace($basic_folder, '', $current_folder)
        ]);
    }
}
