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
use basteyy\Webstatt\Enums\ConfigFile;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;
use function basteyy\VariousPhpSnippets\write_ini_file;

class MailSettingsController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::ADMIN;

    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $mail_config = $this->getConfigService()->getMailConfig();

        if ($this->isPost()) {
            if (!in_array($request->getParsedBody()['smtp_secure'], ['tls', 'ssl'])) {
                FlashMessages::addErrorMessage(__('You send an invalid value %s', $request->getParsedBody()['smtp_secure']));
            } elseif (!in_array($request->getParsedBody()['mailer_method'], ['mail', 'sendmail'])) {
                FlashMessages::addErrorMessage(__('You send an invalid value %s', $request->getParsedBody()['smtp_secure']));
            } else {

                write_ini_file($this->getConfigService()->getMailConfigPath(),
                    array_merge($mail_config, [
                        'activate_mail_system' => isset($request->getParsedBody()['activate_mail_system']),
                        'name'                 => $request->getParsedBody()['name'],
                        'from'                 => $request->getParsedBody()['from'],
                        'reply'                => $request->getParsedBody()['reply'],
                        'lang'                => $request->getParsedBody()['lang'],
                        'mailer_method'                => $request->getParsedBody()['mailer_method'],
                        'smtp_host'            => $request->getParsedBody()['smtp_host'],
                        'smtp_port'            => $request->getParsedBody()['smtp_port'],
                        'smtp_auth_required'   => isset($request->getParsedBody()['smtp_auth_required']),
                        'smtp_username'        => $request->getParsedBody()['smtp_username'],
                        'smtp_password'        => $request->getParsedBody()['smtp_password'],
                        'smtp_secure'          => $request->getParsedBody()['smtp_secure'],
                        'smtp_activated'       => isset($request->getParsedBody()['smtp_activated']),
                        'smtp_server_debug'       => isset($request->getParsedBody()['smtp_server_debug'])

                    ]),
                    (file_exists(SRC . 'Resources/StaticFiles/Ini/header.ini') ? file_get_contents(SRC . 'Resources/StaticFiles/Ini/header.ini') : ''),
                    '; Generated on ' . date('d.m.y H:i:s'));

                /* Free APCU Cache */
                if (APCU_SUPPORT) {
                    apcu_delete(ConfigFile::MAIL->cacheName());
                }

                FlashMessages::addSuccessMessage(__('Changes saved'));

            }

            return $this->adminRedirect('settings/email');

        }

        return $this->adminRender('settings/email', [
            'mail_config' => $mail_config
        ]);
    }
}