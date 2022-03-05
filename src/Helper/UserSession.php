<?php

declare(strict_types=1);

namespace basteyy\Webstatt\Helper;

use basteyy\Webstatt\Enums\UserRole;
use JetBrains\PhpStorm\Pure;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use SlimSession\Helper;
use function basteyy\VariousPhpSnippets\varDebug;

class UserSession
{
    private static Helper $session;

    private static string $user_session_key = 'user_session';

    public function __construct(Helper $session)
    {
        self::$session = $session;
    }

    public function __invoke(
        Request $request,
        RequestHandler $handler
    ): Response {

        // Nothing to do for the moment

        return $handler->handle($request);
    }

    public static function startUserSession(int $user_id) : void {
        self::$session->set(self::$user_session_key, $user_id);

    }

    public static function endUserSession(bool $destroy = false) : void {
        self::$session->delete(self::$user_session_key);
        if($destroy) {
            self::$session->destroy();
        }
    }

    #[Pure] public static function activeUserSession() : bool {
        return self::$session->exists(self::$user_session_key);
    }

    public static function getUserSessionData() : int|false {
        return self::$session->exists(self::$user_session_key) ? self::$session->get(self::$user_session_key) : false;
    }
}