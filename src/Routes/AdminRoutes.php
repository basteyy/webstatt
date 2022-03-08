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

use basteyy\Webstatt\Controller\Content\AddContentController;
use basteyy\Webstatt\Controller\Content\ContentOverview;
use basteyy\Webstatt\Controller\Content\EditContentController;
use basteyy\Webstatt\Controller\Content\RestoreContentVersionController;
use basteyy\Webstatt\Controller\Content\ViewContentVersionController;
use basteyy\Webstatt\Controller\DashboardController;
use basteyy\Webstatt\Controller\Files\FilesOverviewController;
use basteyy\Webstatt\Controller\LoginController;
use basteyy\Webstatt\Controller\LogoutController;
use basteyy\Webstatt\Controller\Profile\UserChangeMailController;
use basteyy\Webstatt\Controller\Profile\UserProfilController;
use basteyy\Webstatt\Controller\Users\AddUserController;
use basteyy\Webstatt\Controller\Users\ListUsersController;
use basteyy\Webstatt\Controller\Users\RemoveUserController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

if (!isset($this->app)) {
    throw new Exception(sprintf('Not allowed to access %s', __FILE__));
}

/** The following routes are namespaced under /admin are and are used in the backend */
$this->app->group('/admin', function (RouteCollectorProxy $proxy) {

    /** No Namespace Routes */

    /** Login */
    $proxy->any('', LoginController::class);

    /** Logout */
    $proxy->any('/logout', LogoutController::class);

    /** View Dashboard */
    $proxy->any('/dashboard', DashboardController::class);

    /** User Account */
    $proxy->group('/account', function (RouteCollectorProxy $proxy) {

        /** Change Profile-Data */
        $proxy->any('', UserProfilController::class);

        /** Change E-Mail */
        $proxy->any('/email', UserChangeMailController::class);
    });

    /** User Management Routes */
    $proxy->group('/users', function (RouteCollectorProxy $proxy) {

        /** List all Users */
        $proxy->any('', ListUsersController::class);

        /** Add a new User */
        $proxy->any('/add', AddUserController::class);

        /** Delete a User */
        $proxy->any('/delete/{user_secret}', RemoveUserController::class);
    });


    /** Content Pages Routes */
    $proxy->group('/content/pages', function (RouteCollectorProxy $proxy) {

        /** List all Content Pages */
        $proxy->any('', ContentOverview::class);

        /** Add a new Content Page */
        $proxy->any('/add', AddContentController::class);

        /** Edit a Content Page */
        $proxy->any('/edit/{content_page_secret}', EditContentController::class);

        /** View a version of a page */
        $proxy->any('/edit/{content_page_secret}/version/{version_file_name}', ViewContentVersionController::class);

        /** Restore a version of a page */
        $proxy->any('/edit/{content_page_secret}/restore/{version_file_name}', RestoreContentVersionController::class);
    });


    /** Files Routes */
    $proxy->group('/files', function (RouteCollectorProxy $proxy) {
        $proxy->get('', FilesOverviewController::class);
    });

    /** Global Settings */
    $proxy->group('/settings', function (RouteCollectorProxy $proxy) {
        $proxy->get('', \basteyy\Webstatt\Controller\Settings\SettingsOverviewController::class);
    });



});