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

class EditSnippetController extends Controller {

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

        if($this->isPost()) {
            $data = [
                'name'    => $request->getParsedBody()['name'],
                'key'     => $request->getParsedBody()['key'],
                'content' => $request->getParsedBody()['content'],
                'active' => (bool)$request->getParsedBody()['active'],
                'cache' => (bool)$request->getParsedBody()['cache'],
                'secret' => getRandomString(16),
            ];

            if($this->isValidUpdateData($data, $snippet)) {
                $this->getSnippetsModel()->patch($snippet, $data);
                FlashMessages::addSuccessMessage(__('Snippet was updated'));

                if(file_exists($snippet->getCachedFileRealPath())) {
                    unlink($snippet->getCachedFileRealPath());
                }

                if(APCU_SUPPORT) {
                    apcu_delete('snippet_' . $snippet->getId());
                }

                return $this->adminRedirect('snippets/edit/' . $data['secret']);
            }

            return $this->adminRedirect();

        }

        return $this->adminRender('snippets/edit', [
            'snippet' => $snippet
        ]);
    }

}