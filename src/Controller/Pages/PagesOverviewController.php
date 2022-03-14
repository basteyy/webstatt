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
use basteyy\Webstatt\Enums\UserRole;
use Psr\Http\Message\ResponseInterface;

class PagesOverviewController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    public function __invoke(): ResponseInterface
    {
        return $this->adminRender('pages/overview', [
            'pages' => $this->getPagesModel()->getAll()
        ]);
    }
}