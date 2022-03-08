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

namespace basteyy\Webstatt\Controller\Settings;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
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

class SettingsOverviewController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $access_file = PACKAGE_ROOT . DS . 'access.ini';

        if(!file_exists($access_file)) {
            throw new \Exception(__('Access-File not found'));
        }



        return $this->render('Webstatt::settings/overview', [
        ]);
    }
}