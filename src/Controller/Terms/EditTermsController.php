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

namespace basteyy\Webstatt\Controller\Terms;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Controller\Traits\TermTrait;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function basteyy\VariousPhpSnippets\__;

class EditTermsController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::USER;

    use TermTrait;

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->isPost()) {
            $this->setTermContent($request->getParsedBody()['terms']);
            FlashMessages::addSuccessMessage(__('Terms updated'));

            return $this->reload();
        }

        return $this->adminRender('terms/edit', [
            'term' => $this->getTermContent()
        ]);

    }
}