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

namespace basteyy\Webstatt\Helper;

use basteyy\Webstatt\Services\ConfigService;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use function basteyy\VariousPhpSnippets\__;

final class MailHelper {

    private ConfigService $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * Check if the mail system is enabled
     * @return bool
     */
    public function isEnabled() : bool {
        return $this->configService->getMailConfig()['activate_mail_system'];
    }

    /**
     * Prepare and get a new instance of PHPMailer
     * @throws Exception
     * @see PHPMailer
     */
    public function newMail() : PHPMailer {

        $mail = new PHPMailer($this->configService->debug);

        $mail->setFrom($this->configService->getMailConfig()['from'], $this->configService->getMailConfig()['name']);
        $mail->addReplyTo($this->configService->getMailConfig()['reply'], $this->configService->getMailConfig()['name']);

        /**Just neerds will read that later */
        $mail->addCustomHeader('X-Powered-by', 'Webstatt (https://github.com/basteyy/webstatt)');

        $mail->setLanguage($this->configService->getMailConfig()['lang']);

        if($this->configService->getMailConfig()['smtp_activated']) {

            /**SMTP MAIL */
            $mail->isSMTP();

            $mail->SMTPDebug = $this->configService->getMailConfig()['smtp_server_debug'] ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;

            $mail->Host = $this->configService->getMailConfig()['smtp_host'];
            $mail->Port = $this->configService->getMailConfig()['smtp_port'];

            $mail->SMTPAuth = $this->configService->getMailConfig()['smtp_auth_required'];
            $mail->Username = $this->configService->getMailConfig()['smtp_username'];
            $mail->Password = $this->configService->getMailConfig()['smtp_password'];

            $mail->SMTPSecure = match($this->configService->getMailConfig()['smtp_secure']) {
                'tls' => PHPMailer::ENCRYPTION_STARTTLS,
                'ssl' => PHPMailer::ENCRYPTION_SMTPS
            };

        } else {

            if('mail' === $this->configService->getMailConfig()['mailer_method'] ) {

                /**Mail */
                $mail->isMail();

            } elseif ( 'sendmail' === $this->configService->getMailConfig()['mailer_method'] ) {

                /**Sendmail */
                $mail->isSendmail();

            } else {
                throw new Exception(__('Incorrect Mail Method selected: %s', $this->configService->getMailConfig()['mailer_method']));
            }

        }

        return $mail;
    }


}
