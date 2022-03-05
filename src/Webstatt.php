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
use basteyy\Webstatt\Controller\Content\DispatchContentController;
use basteyy\Webstatt\Helper\AdminNavbarItem;
use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Helper\UserSession;
use basteyy\Webstatt\Services\ConfigService;
use DI\Bridge\Slim\Bridge;
use DI\ContainerBuilder;
use Exception;
use Invoker\Exception\NotCallableException;
use League\Plates\Engine;
use League\Plates\Extension\URI;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use SleekDB\Store;
use Slim\App;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Middleware\Session;
use Slim\Psr7\Request;
use SlimSession\Helper;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function basteyy\VariousPhpSnippets\varDebug;
use function DI\create;

class Webstatt
{
    /** @var App */
    private App $app;

    /** @var RequestInterface */
    private RequestInterface $request;

    /** @var ContainerInterface */
    private ContainerInterface $container;

    /** @var array */
    private array $config;
    private array $template_navbar_items = [];

    public function __construct(array $options)
    {
        if(isset($options['debug']) && is_bool($options['debug'])) {
            $this->config['debug'] = $options['debug'];
        }

        /** Yes, i'am lazy */
        define('DS', DIRECTORY_SEPARATOR);

        /** Every good page needs a root. That's mine root */
        define('ROOT', dirname(__DIR__, 3));

        /** Direct path to the ROOT Folder of the Webstatt-Package folder */
        define('PACKAGE_ROOT', dirname(__DIR__) . DS);

        /** Direct path to the src folder */
        define('SRC', PACKAGE_ROOT . DS . 'src' . DS);

        /** Put trash here */
        define('TEMP', ROOT . DS . 'cache' . DS);

        /** Base Public Folder in Filesystem */
        define('PUB', ROOT . DS . 'public' . DS);

        try {

            /** Try to load default config from config.ini */
            if (!file_exists(PACKAGE_ROOT . DS . 'config.ini')) {
                throw new Exception(sprintf('No default-config file found at "%s"', PACKAGE_ROOT . DS . 'config.ini'));
            }

            $this->config = parse_ini_file(PACKAGE_ROOT . DS . 'config.ini', false);

            /** Try to load the config from the config.ini in root folder */
            if (file_exists(ROOT . DS . 'config.ini')) {
                $this->config = array_merge($this->config, parse_ini_file(ROOT . DS . 'config.ini', false));
            }

            /** Make the temp folder */
            if (!is_dir(TEMP)) {
                mkdir(TEMP, 0755, true);
            }

            /** Make the composer magic happen */
            require ROOT . DS . 'vendor' . DS . 'autoload.php';

            /** Yes, I create  the request here manually. See below for the why */
            /** @var Request $request */
            $this->request = (ServerRequestCreatorFactory::create())->createServerRequestFromGlobals();

            if ('POST' !== $this->request->getMethod()) {
                /** I know, normally a middleware should do that. But that's seems faster to me */
                if (str_ends_with($this->request->getUri()->getPath(), '/')) {

                    $url =
                        // Base Path
                        substr($this->request->getUri()->getPath(), 0, -1) .
                        // Query?
                        (strlen($this->request->getUri()->getQuery()) > 0 ? '?' . $this->request->getUri()->getQuery() : '') .
                        // Fragment?
                        (strlen($this->request->getUri()->getFragment()) > 0 ? '#' . $this->request->getUri()->getFragment() : '');

                    /** In case there is no domainname (by using ip address) */
                    if (strlen($url) !== 0) {
                        http_response_code(301);
                        header('location: ' . $url);
                        exit();
                    }
                }
            }

            /** Lets build the awesome container */
            $builder = new ContainerBuilder();
            $builder->useAnnotations(false);

            $request = $this->request;

            $builder->addDefinitions([
                'config' => $this->config,

                ConfigService::class => create()->constructor($this->config),

                /** Session */
                'session'            => function () {
                    /** @see https://github.com/bryanjhv/slim-session */
                    return new Helper();
                },

                /** Template Engine */
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

            $this->app = Bridge::create($builder->build());


            /** Session @see https://github.com/bryanjhv/slim-session */
            $this->app->add(
                new Session([
                    'name'        => $this->config['session_name'],
                    'autorefresh' => $this->config['session_auto_refresh'],
                    'lifetime'    => $this->config['session_timeout'],
                ])
            );

            $this->app->add(FlashMessages::class);
            $this->app->add(UserSession::class);

            if (file_exists(SRC . 'routes' . DS . 'WebsiteRoutes.php')) {
                include SRC . 'Routes' . DS . 'WebsiteRoutes.php';

                /** Dispatch the content page routes */
                $pages = new Store($this->config['database_pages_name'], ROOT . DS . $this->config['database_folder'], [
                    'timeout'     => false,
                    'primary_key' => $this->config['database_primary_key']
                ]);

                foreach ($pages->findAll() as $page) {
                    $this->app->get('/' . $page['url'], DispatchContentController::class);
                }
            }

            if (str_starts_with($this->request->getUri()->getPath(), '/admin') && file_exists(SRC . 'routes' . DS . 'AdminRoutes.php')) {

                /** In the admin Szenario, there will be the l18n helper loaded */
                i18n::addTranslationFolder(SRC . 'Resources' . DS . 'Languages');
                i18n::setTranslationLanguage('de_DE');

                include SRC . 'Routes' . DS . 'AdminRoutes.php';
            }

        } catch (Exception|NotCallableException|Throwable $exception) {
            if (isset($this->config) && ($this->config['debug'] || $this->config['website'] === 'development')) {
                $this->displayDebugPage($exception);
            } else {
                $this->displayErrorPage();
            }
        }

    }

    private function displayDebugPage(Throwable $exception)
    {
        (new Run())->pushHandler(new PrettyPageHandler())->handleException($exception);
    }

    private function displayErrorPage()
    {
        ob_clean();
        http_response_code(501);
        echo file_get_contents(SRC . 'Templates' . DS . 'layouts' . DS . 'offline.php');
        die();
    }

    public function addAdminNavbarItem(AdminNavbarItem $item)
    {
        $this->template_navbar_items[] = (string)$item;
    }

    /**
     * Push layouts, you may have in the template structure of your website, to the content dispatcher function for displaying content pages
     * @param array $layouts
     * @return void
     */
    public function addWebsiteTemplateLayouts(array $layouts) : void {
        $this->template_layouts = $layouts;
    }

    public function addWebsiteTemplateFolder(string $template_folder): void
    {
        /** @var Engine $engine */
        $engine = $this->getApp()->getContainer()->get(Engine::class);

        $engine->setDirectory($template_folder);
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function run()
    {
        try {

            /** Push more admin navbar items to the template */
            if ($this->template_navbar_items && count($this->template_navbar_items) > 0) {
                $this->getApp()->getContainer()->get(Engine::class)->addData(['additional_admin_nav_items' => $this->template_navbar_items], 'Webstatt::layouts/acp');
            }


            $this->getApp()->run($this->request);
        } catch (Exception|NotCallableException|Throwable $exception) {
            if (isset($this->config) && ($this->config['debug'] || $this->config['website'] === 'development')) {
                $this->displayDebugPage($exception);
            } else {
                $this->displayErrorPage();
            }
        }
    }
}
