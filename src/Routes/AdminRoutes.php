<?php
/**
 * Webstatt
 *
 * @author Sebastian Eiweleit <sebastian@eiweleit.de>
 * @website https://webstatt.org
 * @website https://github.com/basteyy/webstatt
 * @license CC BY-SA 4.0
 */

/** @var $app App */
declare(strict_types=1);

use basteyy\Webstatt\Controller\DashboardController;
use basteyy\Webstatt\Controller\LoginController;
use basteyy\Webstatt\Controller\LogoutController;
use basteyy\Webstatt\Controller\Users\AddUserController;
use basteyy\Webstatt\Controller\Users\ListUsersController;
use basteyy\Webstatt\Controller\Users\RemoveUserController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

if (!isset($this->app)) {
    throw new Exception(sprintf('Not allowed to access %s', __FILE__));
}


$this->app->group('/admin', function (RouteCollectorProxy $proxy) {

    $proxy->any('', LoginController::class);
    $proxy->any('/dashboard', DashboardController::class);
    $proxy->any('/logout', LogoutController::class);

    $proxy->any('/me/email', \basteyy\Webstatt\Controller\Profile\UserChangeMailController::class);
    $proxy->any('/me', \basteyy\Webstatt\Controller\Profile\UserProfilController::class);

    $proxy->any('/users', ListUsersController::class);
    $proxy->any('/users/add', AddUserController::class);
    $proxy->any('/users/delete/{user_secret}', RemoveUserController::class);


    $proxy->any('/content', \basteyy\Webstatt\Controller\Content\ContentOverview::class);
    $proxy->any('/content/add', \basteyy\Webstatt\Controller\Content\AddContentController::class);
    $proxy->any('/content/edit/{content_page_secret}', \basteyy\Webstatt\Controller\Content\EditContentController::class);
    $proxy->any('/content/edit/{content_page_secret}/version/{version_file_name}', \basteyy\Webstatt\Controller\Content\ViewContentVersionController::class);
    $proxy->any('/content/edit/{content_page_secret}/restore/{version_file_name}', \basteyy\Webstatt\Controller\Content\RestoreContentVersionController::class);

});