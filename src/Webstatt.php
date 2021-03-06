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

namespace basteyy\Webstatt;

use basteyy\PlatesLocalAssetsCopy\PlatesLocalAssetsCopy;
use basteyy\PlatesUrlToolset\PlatesUrlToolset;
use basteyy\VariousPhpSnippets\i18n;
use basteyy\Webstatt\Controller\Pages\DispatchPageController;
use basteyy\Webstatt\Helper\AdminNavbarItem;
use basteyy\Webstatt\Helper\EngineExtensions\ContentPageLayoutHelper;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserSession;
use basteyy\Webstatt\Models\Entities\PageEntity;
use basteyy\Webstatt\Models\PagesModel;
use basteyy\Webstatt\Services\AccessService;
use basteyy\Webstatt\Services\ConfigService;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Exception;
use FastRoute\BadRouteException;
use Invoker\Exception\NotCallableException;
use JetBrains\PhpStorm\NoReturn;
use League\Plates\Engine;
use League\Plates\Extension\URI;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Middleware\Session;
use Slim\Psr7\Request;
use SlimSession\Helper;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function basteyy\VariousPhpSnippets\varDebug;

class Webstatt
{
    /** @var App */
    private App $app;

    /** @var RequestInterface */
    private RequestInterface $request;

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var ConfigService $configService */
    private ConfigService $configService;

    /** @var array Array for Navbar Items */
    private array $template_navbar_items = [];

    /** @var array Array for the supported layouts */
    private array $template_layouts = [];

    public function __construct(array $options = [])
    {
        if (!defined('FAST_HASH')) {
            define('FAST_HASH', 'xxh3');
        }

        /** Yes, i'am lazy */
        define('DS', DIRECTORY_SEPARATOR);

        /** Root of the website (not root of webstatt) */
        if (!defined('ROOT')) {
            define('ROOT', dirname(__DIR__, 3));
        }

        /** Direct path to the ROOT Folder of the Webstatt-Package folder */
        define('PACKAGE_ROOT', dirname(__DIR__) . DS);

        /** Direct path to the src folder */
        define('SRC', PACKAGE_ROOT . 'src' . DS);

        try {

            /**Try to load default config from config.ini */
            if (!file_exists(PACKAGE_ROOT . DS . 'config.ini')) {
                throw new Exception(sprintf('No default-config file found at "%s"', PACKAGE_ROOT . DS . 'config.ini'));
            }

            /** Load the default config */
            $config = parse_ini_file(PACKAGE_ROOT . DS . 'config.ini', false);

            /** Try to load the config from the config.ini in root folder */
            if (file_exists(ROOT . DS . 'config.ini')) {
                $config = array_merge($config, parse_ini_file(ROOT . DS . 'config.ini', false));
            }

            /** Is Debug-Mode forced? */
            if (isset($options['debug']) && is_bool($options['debug'])) {
                $config['debug'] = $options['debug'];
            }

            /** Construct the Congi Servcie */
            $this->configService = new ConfigService($config);

            include SRC . 'Constants.php';


            /**Make the temp folder */
            if (!is_dir(TEMP)) {
                mkdir(TEMP, WEBSTATT_DEFAULT_FOLDER_PERMISSIONS, WEBSTATT_CREATE_FOLDER_RECURSIVE);
            }

            /**Access Service Initiation */
            $accessService = new AccessService($this->configService);

            /**Yes, I create  the request here manually. See below for the why */
            /** @var Request $request */
            $this->request = (ServerRequestCreatorFactory::create())->createServerRequestFromGlobals();

            if ('POST' !== $this->request->getMethod()) {
                /**I know, normally a middleware should do that. But that's seems faster to me */
                if (str_ends_with($this->request->getUri()->getPath(), '/')) {

                    $url =
                        // Base Path
                        substr($this->request->getUri()->getPath(), 0, -1) .
                        // Query?
                        (strlen($this->request->getUri()->getQuery()) > 0 ? '?' . $this->request->getUri()->getQuery() : '') .
                        // Fragment?
                        (strlen($this->request->getUri()->getFragment()) > 0 ? '#' . $this->request->getUri()->getFragment() : '');

                    /**In case there is no domainname (by using ip address) */
                    if (strlen($url) !== 0) {
                        http_response_code(301);
                        header('location: ' . $url);
                        exit();
                    }
                }
            }

            /**Let's build the awesome container */
            $builder = new ContainerBuilder();
            $builder->useAnnotations(false);

            /**We need the request object later a few times inside the DI definitions */
            $request = $this->request;

            $builder->addDefinitions([
                ConfigService::class => $this->configService,

                AccessService::class => $accessService,

                ServerRequestInterface::class => $this->request,

                /**Session */
                'session'            => function () {
                    /** @see https://github.com/bryanjhv/slim-session */
                    return new Helper();
                },

                /**Template Engine */
                Engine::class        => function () use ($request) {

                    $engine = new Engine();

                    $engine->addFolder('Webstatt', SRC . 'Templates');

                    $engine->loadExtensions([
                        new PlatesUrlToolset(),
                        new PlatesLocalAssetsCopy(PUB . 'cache', $request->getUri()->getScheme() . '://' . $request->getUri()->getHost() . DS . 'cache' . DS),
                        new URI($request->getUri())
                    ]);

                    return $engine;
                }
            ]);

            /**Build the app from DI Builder */
            $this->app = Bridge::create($builder->build());


            /**Session @see https://github.com/bryanjhv/slim-session */
            $this->app->add(
                new Session([
                    'name'        => $this->configService->session_name,
                    'autorefresh' => $this->configService->session_auto_refresh,
                    'lifetime'    => $this->configService->session_timeout,
                ])
            );

            /**Register the FlashMessages Middleware */
            $this->app->add(FlashMessages::class);

            /**Register the User Session Middleware */
            $this->app->add(UserSession::class);

        } catch (Exception|NotCallableException|Throwable $exception) {
            $this->handleException($exception);
        }

    }

    /**
     * Run Exception Handler in Debug State
     * @param Throwable $exception
     * @return void
     */
    private function handleException(Throwable $exception): void
    {
        (new Run())->pushHandler(new PrettyPageHandler())->handleException($exception);
        #if (isset($this->configService) && ($this->configService->debug || $this->configService->website === 'development')) {
        #    (new Run())->pushHandler(new PrettyPageHandler())->handleException($exception);
        #} else {
        #    $this->displayErrorPage();
        #}
    }

    /**
     * Output a error page
     * @param int $status_code
     * @return void
     */
    #[NoReturn] private function displayErrorPage(int $status_code = 501): void
    {
        ob_clean();
        http_response_code($status_code);
        echo file_get_contents(SRC . 'Templates' . DS . 'layouts' . DS . 'offline.php');
        die();
    }

    /**
     * Add an item to the navbar
     * @param AdminNavbarItem $item
     * @return void
     */
    public function addAdminNavbarItem(AdminNavbarItem $item): void
    {
        $this->template_navbar_items[] = $item;
    }

    /**
     * Add an array of AdminNavbarItems to the acp
     * @param array $items
     * @return void
     */
    public function addAdminNavbarItems(array $items): void
    {
        foreach($items as $item) {
            $this->addAdminNavbarItem($item);
        }
    }

    /**
     * Push layouts, you may have in the template structure of your website, to the content dispatcher function for displaying content pages
     * @param array $layouts
     * @return void
     */
    public function addWebsiteTemplateLayouts(array $layouts): void
    {
        $this->template_layouts = $layouts;
    }

    /**
     * Add your website template folder to the scope
     * @param string $template_folder
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function addWebsiteTemplateFolder(string $template_folder): void
    {
        /** @var Engine $engine */
        $engine = $this->getApp()->getContainer()->get(Engine::class);

        $engine->setDirectory($template_folder);
    }

    /**
     * Get the Slim App
     * @return App
     */
    public function getApp(): App
    {
        return $this->app;
    }

    /**
     * @throws \SleekDB\Exceptions\IOException
     * @throws \ReflectionException
     * @throws \SleekDB\Exceptions\InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    private function attachWebstattRoutes() {
        /** Startpage */

        if(!isset($pagesModel)) {
            $pagesModel = (new PagesModel($this->configService));
        }

        /** @var  $startpage */
        $startpage = $pagesModel->getStartpage();

        if('/' === $this->request->getUri()->getPath() || '' === $this->request->getUri()->getPath() && $startpage) {

            $root_route_defined = false;

            if(APCU_SUPPORT && apcu_exists('custom_root_route')) {
                $root_route_defined = apcu_fetch('custom_root_route');
            } else {
                foreach($this->app->getRouteCollector()->getRoutes() as $route) {
                    if($route->getPattern() === '/') {
                        // Route found!
                        $root_route_defined = true;
                    }
                }

                if(APCU_SUPPORT) {
                    apcu_add('custom_root_route', $root_route_defined, APCU_TTL_MEDIUM);
                }
            }

            if(!$root_route_defined) {
                $this->app->get('/', DispatchPageController::class);
            }

        } elseif (str_starts_with($this->request->getUri()->getPath(), '/admin') && file_exists(SRC . 'Routes' . DS . 'AdminRoutes.php')) {

            /**In the admin Szenario, there will be the l18n helper loaded */
            i18n::addTranslationFolder(SRC . 'Resources' . DS . 'Languages');
            i18n::setTranslationLanguage('de_DE');

            /**Include the Admin Routes */
            include SRC . 'Routes' . DS . 'AdminRoutes.php';
        } else {

            /**Static Webstatt Website Routes? */
            if (file_exists(SRC . 'Routes' . DS . 'WebsiteRoutes.php')) {
                include SRC . 'Routes' . DS . 'WebsiteRoutes.php';
            }

            /**Cache enabled and cache exists? */
            if((APCU_SUPPORT && !apcu_exists(W_PAGES_ROUTES_CACHE_KEY)) || !APCU_SUPPORT) {

                if(!isset($pagesModel)) {
                    $pagesModel = (new PagesModel($this->configService));
                }

                $pages = [];

                /** @var PageEntity $page */
                foreach($pagesModel->getAllOnlinePages(false) as $page) {
                    if(null !== $page ) {
                        $pages[] = $page->getUrl();
                    }
                }

                /**Put to cache, in case its enabled */
                if(APCU_SUPPORT) {
                    /**Store to cache */
                    apcu_add(W_PAGES_ROUTES_CACHE_KEY, $pages, APCU_TTL_LONG);
                }

            } else {
                /**Routes from cache */
                $pages = apcu_fetch(W_PAGES_ROUTES_CACHE_KEY);
            }

            foreach ($pages as $x => $url) {
                $this->app->get($url, DispatchPageController::class);
            }

        }
    }

    /**
     * Run Webstatt
     * @return void
     */
    public function run()
    {
        try {

            /** Apply the Webstatt Routes */
            $this->attachWebstattRoutes();

            /** Push more admin navbar items to the template */
            if ($this->template_navbar_items && count($this->template_navbar_items) > 0) {
                $this->getApp()->getContainer()->get(Engine::class)->addData(['additional_admin_nav_items' => $this->template_navbar_items], 'Webstatt::acp');
            }

            /** Pages Layout */
            $this->getApp()->getContainer()->get(Engine::class)->loadExtension(
                new ContentPageLayoutHelper($this->template_layouts)
            );

            /** Run Slim 4 */
            $this->getApp()->run($this->request);

        } catch (Exception|NotCallableException|Throwable $exception) {
            $this->handleException($exception);
        }
    }
}
