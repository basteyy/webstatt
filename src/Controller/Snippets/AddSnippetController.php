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
use basteyy\Webstatt\Helper\FlashMessages;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getRandomString;

class AddSnippetController extends Controller
{

    /**
     * @throws IOException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {

        if ($this->isPost()) {
            $data = [
                'name'    => $request->getParsedBody()['name'],
                'key'     => $request->getParsedBody()['key'],
                'content' => $request->getParsedBody()['content'],
                'active' => (bool)$request->getParsedBody()['active'],
                'cache' => (bool)$request->getParsedBody()['cache'],
                'secret' => getRandomString(16),
            ];

            $this->getSnippetsModel()->create($data);

            FlashMessages::addSuccessMessage(__('Snippets was created'));

            return $this->adminRedirect('/snippets#' . $this->getSnippetsModel()->getRaw()->getLastInsertedId());

        }


        return $this->adminRender('snippets/add', [
            'snippet' => []
        ]);
    }

}