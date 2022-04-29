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
use basteyy\Webstatt\Helper\MailHelper;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;
use function basteyy\VariousPhpSnippets\write_ini_file;

class MailSettingsController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::ADMIN;

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {
        $mail_config = $this->getConfigService()->getMailConfig();

        if ($this->isPost()) {

            $errors = [];
            $mail_settings = [];

            /** Check the SMTP Login Data */
            if (isset($request->getParsedBody()['smtp_auth_required'])) {

                if (!in_array($request->getParsedBody()['smtp_secure'], ['tls', 'ssl'])) {
                    $errors[] = __('Invalid setting for smtp secure-type');
                } else {
                    $mail_settings += [
                        'smtp_auth_required' => isset($request->getParsedBody()['smtp_auth_required']),
                        'smtp_username'      => $request->getParsedBody()['smtp_username'],
                        'smtp_password'      => $request->getParsedBody()['smtp_password'],
                        'smtp_secure'        => $request->getParsedBody()['smtp_secure'],
                    ];
                }
            }

            // Check mailer method
            if (!in_array($request->getParsedBody()['mailer_method'], ['mail', 'sendmail'])) {
                $errors[] = __('You send an invalid value %s', $request->getParsedBody()['smtp_secure']);
            }

            if (count($errors) < 1) {
                // save

                $mail_settings += [
                    'activate_mail_system' => isset($request->getParsedBody()['activate_mail_system']),
                    'name'                 => $request->getParsedBody()['name'],
                    'from'                 => $request->getParsedBody()['from'],
                    'reply'                => $request->getParsedBody()['reply'],
                    'lang'                 => $request->getParsedBody()['lang'],
                    'mailer_method'        => $request->getParsedBody()['mailer_method'],
                    'smtp_host'            => $request->getParsedBody()['smtp_host'],
                    'smtp_port'            => $request->getParsedBody()['smtp_port'],
                    'smtp_activated'       => isset($request->getParsedBody()['smtp_activated']),
                    'smtp_server_debug'    => isset($request->getParsedBody()['smtp_server_debug'])
                ];

                write_ini_file(
                    $this->getConfigService()->getMailConfigPath(),
                    array_merge($mail_config, $mail_settings),
                    (file_exists(SRC . 'Resources/StaticFiles/Ini/header.ini') ? file_get_contents(SRC . 'Resources/StaticFiles/Ini/header.ini') : ''),
                    '; Generated on ' . date('d.m.y H:i:s'));

                /**Free APCU Cache */
                if (APCU_SUPPORT) {
                    apcu_delete(ConfigFile::MAIL->cacheName());
                }

                if (isset($request->getParsedBody()['test_recipient'])) {
                    if (filter_var($request->getParsedBody()['test_recipient'], FILTER_VALIDATE_EMAIL)) {

                        $mailhelper = new MailHelper($this->getConfigService());

                        if ($mailhelper->isEnabled()) {
                            $mail = $mailhelper->newMail();
                            $mail->addAddress($request->getParsedBody()['test_recipient']);
                            $mail->isHTML();
                            $mail->Subject = __('That\'s a testmail');
                            $mail->Body = $this->getEngine()->render('Webstatt::mail/test_mail', [
                                'user' => $this->getCurrentUser()
                            ]);
                            $mail->send();

                            if($mail->isError()) {
                                FlashMessages::addErrorMessage(__('Sending the testmail failed. Error(s): %s', $mail->ErrorInfo));
                            } else {
                                FlashMessages::addSuccessMessage(__('Testmail was send successfully. Check out the mailbox %s', $request->getParsedBody()['test_recipient']));
                            }

                        } else {
                            FlashMessages::addErrorMessage(__('Testmail isn\'t send, because the mail system isn\'t activated/configured'));
                        }

                    } else {
                        FlashMessages::addErrorMessage(__('Testmail isn\'t send, because the recipient address looks invalid'));
                    }
                }

                FlashMessages::addSuccessMessage(__('Changes saved'));

            } else {
                FlashMessages::addErrorMessage($errors);
            }

            return $this->reload();

        }

        return $this->adminRender('settings/email', [
            'mail_config' => $mail_config
        ]);
    }
}