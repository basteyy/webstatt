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

namespace basteyy\Webstatt\Controller;

use basteyy\Webstatt\Controller\Traits\DatabaseTrait;
use basteyy\Webstatt\Controller\Traits\RequestTrait;
use basteyy\Webstatt\Controller\Traits\ResponseTrait;
use basteyy\Webstatt\Controller\Traits\SassCompilerTrait;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserSession;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\AccessService;
use basteyy\Webstatt\Services\ConfigService;
use League\Plates\Engine;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;

/**
 * The Main Controller.
 * @see
 */
class Controller
{
    /** @var UserRole $minimum_user_role Minimum level of user role */
    protected UserRole $minimum_user_role;

    /** @var UserRole $exact_user_role In case the controller is allowed for a specific user role only */
    protected UserRole $exact_user_role;

    /** @var UserAbstraction|null In case the user is logged in, this holds the user data */
    protected UserAbstraction|null $_current_user_data;

    /** @var Engine $engine The Template Rendering Engine */
    private Engine $engine;

    /** @var ConfigService $configService Provider for the current config */
    private ConfigService $configService;

    /** @var AccessService $accessService Provider for accessing checks */
    private AccessService $accessService;

    /** @var array $_activeDatabases A cache for the flat files databases */
    private array $_activeDatabases;

    /** @var ServerRequestInterface $request Request Object */
    private ServerRequestInterface $request;

    /** Sass Compiler Trait */
    use SassCompilerTrait;

    /** Database Trait */
    use DatabaseTrait;

    /** Response Trait (Redirects, Rendering) */
    use ResponseTrait;

    /** Request Trait */
    use RequestTrait;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Engine $engine, ConfigService $configService, AccessService $accessService, ServerRequestInterface $request)
    {

        /** @var RequestInterface request Make the request global in the Controller */
        $this->request = $request;

        $this->engine = $engine;
        $this->configService = $configService;
        $this->accessService = $accessService;

        $this->_current_user_data =
            UserSession::activeUserSession() ? new UserAbstraction(($this->getUserDatabase())->findById(UserSession::getUserSessionData()), $configService) : null;

        $role = $this->_current_user_data ? $this->_current_user_data->getRole() : UserRole::ANONYMOUS;


        if (isset($this->exact_user_role) && $this->exact_user_role !== $role) {

            FlashMessages::addErrorMessage(sprintf('Zugriff auf die Ressourcen nur für Benutzergruppe %s.', $this->exact_user_role->getTitle()));
            header('location: /admin' . ($role === UserRole::ANONYMOUS ? '' : '/dashboard'));
            exit();

        } elseif (isset($this->minimum_user_role)) {


            $allowed = match ($this->minimum_user_role) {
                UserRole::ANONYMOUS => true,
                UserRole::USER => $role === UserRole::USER || $role === UserRole::ADMIN || $role === UserRole::SUPER_ADMIN,
                UserRole::ADMIN => $role === UserRole::ADMIN || $role === UserRole::SUPER_ADMIN,
                UserRole::SUPER_ADMIN => $role === UserRole::SUPER_ADMIN
            };

            if (!$allowed) {
                FlashMessages::addErrorMessage('Du hast keinen Zugriff auf diese Seite. Bitte einloggen.');
                header('location: /admin' . ($role === UserRole::ANONYMOUS ? '' : '/dashboard') . '?not_allowed');
                exit();
            }

        }

    }

    /**
     * Method returns the current user array if logged in.
     * @return UserAbstraction|null
     */
    protected function getCurrentUserData(): UserAbstraction|null
    {
        return $this->_current_user_data ?? null;
    }

    /**
     * Get the Engine (Template) Class
     * @return Engine
     */
    protected function getEngine(): Engine
    {
        return $this->engine;
    }

    /**
     * Get the ConfigService
     * @return ConfigService
     */
    protected function getConfigService(): ConfigService
    {
        return $this->configService;
    }

    /**
     * Get the AccessService
     * @return AccessService
     */
    protected function getAccessService(): AccessService
    {
        return $this->accessService;
    }
}