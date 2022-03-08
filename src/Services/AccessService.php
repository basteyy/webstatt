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

namespace basteyy\Webstatt\Services;

use basteyy\Webstatt\Enums\AccessAction;
use basteyy\Webstatt\Enums\UserRole;
use Exception;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;
use function basteyy\VariousPhpSnippets\write_ini_file;

final class AccessService
{
    private string $access_file_name = 'access.ini';
    private array $access_data;

    private string $apcu_cache_key = 'accessCache';

    /**
     * @throws Exception
     */
    public function __construct(ConfigService $configService)
    {
        /** Load Access Data from Cache? */
        if (APCU_SUPPORT && apcu_exists($this->apcu_cache_key)) {

            /** @var array access_data The Access Data from Cache */
            $this->access_data = apcu_fetch($this->apcu_cache_key);

        } else {

            /* Generate the Access Data */

            $shipped_access_file = PACKAGE_ROOT . $this->access_file_name;
            $custom_access_file = ROOT . $this->access_file_name;

            /** The access.ini from webstatt MUST be there */
            if (!file_exists($shipped_access_file)) {
                //throw new Exception(__('Cant load original access.ini from %s', $shipped_access_file));
                // Generate the Access-File
                $this->buildAccessIni($shipped_access_file);
            }

            /**
             * $access = [
            UserRole::USER->value => [
            AccessAction::CONTENT_CREATE->value => true
            ]
            ];
             */

            $this->access_data = parse_ini_file($shipped_access_file, true);

            /** Overwrite original access settings? */
            if (file_exists($custom_access_file)) {
                $this->access_data = array_merge($this->access_dataa, parse_ini_file($custom_access_file, true));
            }

            /** Add Access Data to the Cache if enabled */
            if(APCU_SUPPORT) {
                apcu_add($this->apcu_cache_key, $this->access_data);
            }
        }
    }

    /**
     * Builda new access.ini
     * @param string $absolute_file_path
     * @param array $access_settings
     * @param bool $grant_super_admin_all
     * @param bool $grant_admin_all
     * @param bool $grant_user_all
     * @return void
     */
    private function buildAccessIni(string $absolute_file_path, array $access_settings = [], bool $grant_super_admin_all = true, bool $grant_admin_all = true, bool
    $grant_user_all = false) : void {

        $build = [];

        /** Loop to the User Roles */
        foreach(UserRole::cases() as $_userRole) {

            /** @var string $userRole Value of the current UserRole */
            $userRole = $_userRole->value;

            /** Loop to the Access Actions */
            foreach(AccessAction::cases() as $_accessAction) {

                /** @var string $accessAction Value of the current AccessType */
                $accessAction = $_accessAction->value;

                /** Looks a bit complicated. But actually checks, if there is any setting provided with $access_settings, if not, if the current User Role is granted */
                $build[$userRole][$accessAction] = $access_settings[$userRole][$accessAction] ?? (
                    ($_userRole === UserRole::SUPER_ADMIN && $grant_super_admin_all) ||
                    ($_userRole === UserRole::ADMIN && $grant_admin_all) ||
                    ($_userRole === UserRole::USER && $grant_user_all) ) ;
            }
        }

        /** Webstatt File Header ? */
        $file_header = file_exists(SRC . 'Resources/StaticFiles/Ini/header.ini') ? file_get_contents(SRC . 'Resources/StaticFiles/Ini/header.ini') : '';

        write_ini_file($absolute_file_path, $build, $file_header, '; Generated on ' . date('d.m.y H:i:s'));
    }

    public function isAllowedTo(UserRole $role, string $required_action_name): bool
    {


    }
}