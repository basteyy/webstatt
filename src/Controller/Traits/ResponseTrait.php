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

namespace basteyy\Webstatt\Controller\Traits;

use basteyy\Webstatt\Models\Abstractions\UserAbstraction;
use basteyy\Webstatt\Services\ConfigService;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;
use function basteyy\VariousPhpSnippets\__;

/**
 * A few Response Traits
 * @see ResponseInterface
 * @see Response
 * @see Engine
 */
trait ResponseTrait {

    /**
     * Return a redirect to `$redirect_uri` as target.
     * @param string $redirect_uri
     * @param int $status_code
     * @param ResponseInterface|null $response
     * @return ResponseInterface
     */
    protected function redirect(string $redirect_uri, int $status_code = 302, ?ResponseInterface $response = null): ResponseInterface
    {

        if (!$response) {
            $response = new Response();
        }

        return $response->withHeader('location', $redirect_uri)->withStatus($status_code);
    }

    /**
     * Render a File Not Found - Error. Method creates a new Response object!
     * @return Response
     */
    protected function render_404(): Response
    {
        $response = new Response();
        $response->withStatus(404);
        $response->getBody()->write(__('File not found'));
        return $response;
    }

    /**
     * Render `$template` with optimal data `$data`. If no `ResponseInterface` is provided, a new Response is created.
     * @param string $template
     * @param array|null $data
     * @param ResponseInterface|null $response
     * @return ResponseInterface
     */
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
}