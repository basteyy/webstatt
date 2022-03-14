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

namespace basteyy\Webstatt\Controller\Cache;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Psr\Http\Message\RequestInterface;
use Slim\Psr7\Request;
use function basteyy\VariousPhpSnippets\__;

final class CacheResetController extends Controller
{
    public UserRole $minimum_user_role = UserRole::ADMIN;


    public function __invoke(RequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        /** @var Request $request */

        apcu_clear_cache();

        FlashMessages::addSuccessMessage(__('Cache was rested'));


        if(str_starts_with($request->getUri()->getQuery(), 'return')) {
            return $this->redirect(substr($request->getUri()->getQuery(), 7));
        }

        return $this->adminRedirect('dashboard');

    }
}
