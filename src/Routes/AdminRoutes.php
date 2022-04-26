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

use basteyy\Webstatt\Controller\Account\ChangeAccountPasswordController;
use basteyy\Webstatt\Controller\Account\UserChangeMailController;
use basteyy\Webstatt\Controller\Account\UserProfilController;
use basteyy\Webstatt\Controller\Cache\CacheResetController;
use basteyy\Webstatt\Controller\DashboardController;
use basteyy\Webstatt\Controller\Files\DeleteFileController;
use basteyy\Webstatt\Controller\Files\EditFileController;
use basteyy\Webstatt\Controller\Files\FilesOverviewController;
use basteyy\Webstatt\Controller\Files\RenameController;
use basteyy\Webstatt\Controller\LoginController;
use basteyy\Webstatt\Controller\LogoutController;
use basteyy\Webstatt\Controller\Pages\AddPageController;
use basteyy\Webstatt\Controller\Pages\EditPageController;
use basteyy\Webstatt\Controller\Pages\PagesOverviewController;
use basteyy\Webstatt\Controller\Pages\RestorePageVersionController;
use basteyy\Webstatt\Controller\Pages\ViewPageVersionController;
use basteyy\Webstatt\Controller\Settings\MailSettingsController;
use basteyy\Webstatt\Controller\Settings\SettingsOverviewController;
use basteyy\Webstatt\Controller\ShowUserProfileController;
use basteyy\Webstatt\Controller\Snippets\AddSnippetController;
use basteyy\Webstatt\Controller\Snippets\DeleteSnippetController;
use basteyy\Webstatt\Controller\Snippets\ListSnippetsController;
use basteyy\Webstatt\Controller\Snippets\EditSnippetController;
use basteyy\Webstatt\Controller\Users\AddUserController;
use basteyy\Webstatt\Controller\Users\EditUserController;
use basteyy\Webstatt\Controller\Users\ListUsersController;
use basteyy\Webstatt\Controller\Users\RemoveUserController;
use basteyy\Webstatt\Controller\Users\UserInvatationController;
use basteyy\Webstatt\Controller\Users\UserSettingsController;
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

    /** Reset Cache */
    $proxy->any('/cache/reset', CacheResetController::class);

    /** Show the User Profile  */
    $proxy->get('/u/{user_secret}', ShowUserProfileController::class);

    /** User Account */
    $proxy->group('/account', function (RouteCollectorProxy $proxy) {

        /** Change Profile-Data */
        $proxy->any('', UserProfilController::class);

        /** Change E-Mail */
        $proxy->any('/email', UserChangeMailController::class);

        /** Change password */
        $proxy->any('/password', ChangeAccountPasswordController::class);
    });

    /** User Management Routes */
    $proxy->group('/users', function (RouteCollectorProxy $proxy) {

        /** List all Users */
        $proxy->any('', ListUsersController::class);

        /** Add a new User */
        $proxy->any('/add', AddUserController::class);

        /** Delete a User */
        $proxy->any('/delete/{user_secret}', RemoveUserController::class);

        /**Edit a user */
        $proxy->any('/edit/{user_secret}', EditUserController::class);

        /** Invitation */
        $proxy->any('/invite', UserInvatationController::class);
        $proxy->any('/invite/create_link', \basteyy\Webstatt\Controller\Users\AddInvatationLinkController::class);

        /** Settings */
        $proxy->any('/settings', UserSettingsController::class);
    });


    /** Pages Routes */
    $proxy->group('/pages', function (RouteCollectorProxy $proxy) {

        /** List all Pages */
        $proxy->any('', PagesOverviewController::class);

        /** Add a new Page */
        $proxy->any('/add', AddPageController::class);

        /** Edit a Page */
        $proxy->any('/edit/{content_page_secret}', EditPageController::class);

        /** View a version of a page */
        $proxy->any('/edit/{content_page_secret}/version/{version_file_name}', ViewPageVersionController::class);

        /** Restore a version of a page */
        $proxy->any('/edit/{content_page_secret}/restore/{version_file_name}', RestorePageVersionController::class);

    });

    /**Snippets */
    $proxy->group('/snippets', function (RouteCollectorProxy $proxy) {

        /**Snippets Overview */
        $proxy->any('', ListSnippetsController::class);

        /**Add a new snippet */
        $proxy->any('/add', AddSnippetController::class);

        /**Edit a snippet */
        $proxy->any('/edit/{snippet_secret}', EditSnippetController::class);

        /**Delete a snippet */
        $proxy->any('/delete/{snippet_secret}', DeleteSnippetController::class);

    });


    /** Files Routes */
    $proxy->group('/files', function (RouteCollectorProxy $proxy) {
        $proxy->any('', FilesOverviewController::class);

        $proxy->get('/delete', DeleteFileController::class);


        $proxy->any('/edit', EditFileController::class);

        $proxy->any('/rename', RenameController::class);

    });

    /** Global Settings */
    $proxy->group('/settings', function (RouteCollectorProxy $proxy) {
        $proxy->get('', SettingsOverviewController::class);

        /**Email Settings */
        $proxy->any('/email', MailSettingsController::class);

    });


});