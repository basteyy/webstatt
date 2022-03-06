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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SlimSession\Helper;

class FlashMessages
{
    private static string $baseKey = 'FLASH_MESSAGES';
    public static string $errorMessages = 'ERROR_MESSAGES';
    public static string $successMessages = 'SUCCESS_MESSAGES';
    private static Helper $session;


    public function __construct(Helper $session)
    {
        self::$session = $session;
    }

    /**
     * Called when middleware needs to be executed.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR7 request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler PSR7 handler
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response {

        // Nothing to do for the moment

        return $handler->handle($request);
    }

    public static function getAllMessages(bool $keep_messages_in_session = false) : array {
        $messages = [];
        $messages += self::$session->exists(self::$baseKey . self::$errorMessages)
            ? [self::$errorMessages => self::$session->get(self::$baseKey . self::$errorMessages) ] : [];
        $messages += self::$session->exists(self::$baseKey . self::$successMessages)
            ? [self::$successMessages => self::$session->get(self::$baseKey . self::$successMessages) ] : [];

        if(!$keep_messages_in_session) {
            self::$session->delete(self::$baseKey . self::$errorMessages);
            self::$session->delete(self::$baseKey . self::$successMessages);
        }

        return $messages;
    }

    public static function addErrorMessage(string $message) : void {
        /** @var array $messages Get all messages from session */
        $messages = self::$session->exists(self::$baseKey . self::$errorMessages) ? self::$session->get(self::$baseKey . self::$errorMessages) : [];

        /** Add a new message to array */
        $messages[] = $message;

        /** Restore the message */
        self::$session->set(self::$baseKey . self::$errorMessages, $messages);
    }

    public static function addSuccessMessage(string $message) : void {

        /** @var array $messages Get all messages from session */
        $messages = self::$session->exists(self::$baseKey . self::$successMessages) ? self::$session->get(self::$baseKey . self::$successMessages) : [];

        /** Add a new message to array */
        $messages[] = $message;

        /** Restore the message */
        self::$session->set(self::$baseKey . self::$successMessages, $messages);
    }

}