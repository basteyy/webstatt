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

use basteyy\ScssPhpBuilder\ScssPhpBuilder;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserSession;
use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Store;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;

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

    /** @var array $_activeDatabases A cache for the flat files databases */
    private array $_activeDatabases;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Engine $engine, ConfigService $configService)
    {


        $this->engine = $engine;

        $this->configService = $configService;

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
     * Return the database instance for the users
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    protected function getUserDatabase(): Store
    {
        return $this->getDatabase($this->configService->database_users_name);
    }

    /**
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    private function getDatabase(string $database): Store
    {

        if (!isset($this->_activeDatabases) || !isset($this->_activeDatabases[$database])) {

            if (!is_dir(ROOT . DS . $this->configService->database_folder)) {
                mkdir(ROOT . DS . $this->configService->database_folder, 0755, true);
            }

            $this->_activeDatabases[$database] = new Store($database, ROOT . DS . $this->configService->database_folder, [
                'timeout'     => false,
                'primary_key' => $this->configService->database_primary_key
            ]);
        }

        return $this->_activeDatabases[$database];
    }

    /**
     * Return the database instance for the pages
     * @throws InvalidConfigurationException
     * @throws IOException
     * @throws InvalidArgumentException
     */
    protected function getContentPagesDatabase(): Store
    {
        return $this->getDatabase($this->configService->database_pages_name);
    }

    protected function redirect(string $redirect_uri, int $status_code = 302, ?ResponseInterface $response = null): ResponseInterface
    {

        if (!$response) {
            $response = new Response();
        }

        return $response->withHeader('location', $redirect_uri)->withStatus($status_code);
    }

    protected function render_404(): Response
    {
        $response = new Response();
        $response->withStatus(404);
        $response->getBody()->write(__('File not found'));
        return $response;
    }

    protected function render(string $template, ?array $data = [], ?ResponseInterface $response = null): ResponseInterface
    {

        if (!$this->getEngine()->exists($template) && !str_starts_with($template, 'Webstatt::')) {
            $template = 'Webstatt::' . $template;
        }

        if (!$response) {
            $response = new Response();
        }

        $this->getEngine()->loadExtension(new class($this->getConfigService()) implements ExtensionInterface {

            private ConfigService $configService;

            public function __construct(ConfigService $configService)
            {
                $this->configService = $configService;
            }

            public function register(Engine $engine)
            {
                $engine->registerFunction('getConfig', fn() => $this->configService);
            }
        });

        /** Patch user data to template if exists */
        $this->getEngine()->loadExtension(new class($this->getCurrentUserData()) implements ExtensionInterface {

            private UserAbstraction|null $data;

            public function __construct(UserAbstraction|null $userData)
            {
                $this->data = $userData;
            }

            public function register(Engine $engine)
            {
                $engine->registerFunction('getUser', fn() => $this->data);
            }

            public function __toString(): string
            {
                return (string)$this->data;
            }
        });

        $this->sassRenderStrategy();

        $response->getBody()->write(
            $this->getEngine()->render($template, $data ?? [])
        );

        return $response;
    }

    protected function getEngine(): Engine
    {
        return $this->engine;
    }

    protected function getConfigService(): ConfigService
    {
        return $this->configService;
    }

    /**
     * Method returns the current user array if logged in.
     * @return array
     */
    protected function getCurrentUserData(): UserAbstraction|null
    {
        return $this->_current_user_data ?? null;
    }

    protected function sassRenderStrategy(): void
    {

        $sass_starting_file = PUB . 'sass' . DS . 'style.scss';
        $css_final_file = PUB . 'css' . DS . 'style.css';

        if (file_exists($sass_starting_file)) {

            $compile = true;

            if (file_exists(PUB . 'css' . DS . 'style.css')) {
                if (filemtime($css_final_file) >= filemtime($sass_starting_file)) {
                    $compile = false;
                }
            }


            if ($compile) {
                $scss = new ScssPhpBuilder();
                $scss->addFolder(PUB . 'sass' . DS);
                $scss->addOutputeFile($css_final_file);
                $scss->addStartingFile($sass_starting_file);
                $scss->compileToOutputfile();
            }

        }

        // Do nothing
    }

    protected function isPost(): bool
    {
        return 'POST' === $_SERVER['REQUEST_METHOD'];
    }
}