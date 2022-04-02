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

namespace basteyy\Webstatt\Controller\Snippets;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Controller\Traits\SnippetsTrait;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use Slim\Psr7\Request;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;

class DeleteSnippetController extends Controller {

    protected UserRole $minimum_user_role = UserRole::USER;

    use SnippetsTrait;

    /**
     * @throws IOException
     * @throws JsonException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function __invoke(RequestInterface $request, string $snippet_secret) : ResponseInterface {
        /** @var Request $request */

        $snippet = $this->checkSnippet($snippet_secret);

        if(!$snippet) {
            FlashMessages::addErrorMessage(__('Snippet %s not found', $snippet_secret));
            return $this->adminRedirect('snippets');
        }

        $this->getSnippetsModel()->delete($snippet);

        if(file_exists($snippet->getCachedFileRealPath())) {
            unlink($snippet->getCachedFileRealPath());
        }

        FlashMessages::addSuccessMessage(__('Snippet %s deleted', $snippet->getKey()));
        return $this->adminRedirect('snippets');
    }

}