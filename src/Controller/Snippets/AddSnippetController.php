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
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use function basteyy\VariousPhpSnippets\__;

class AddSnippetController extends Controller
{

    /**
     * @throws IOException
     * @throws ReflectionException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     */
    public function __invoke(RequestInterface $request): ResponseInterface
    {

        if ($this->isPost()) {
            $data = [
                'name'    => $request->getParsedBody()['name'],
                'key'     => $request->getParsedBody()['key'],
                'content' => $request->getParsedBody()['content'],
            ];

            $this->getSnippetsModel()->create($data);

            FlashMessages::addSuccessMessage(__('Snippets was created'));

            return $this->adminRedirect('/pages/snippets#' . $this->getSnippetsModel()->getRaw()->getLastInsertedId());

        }


        return $this->adminRender('snippets/add', [
            'snippet' => []
        ]);
    }

}