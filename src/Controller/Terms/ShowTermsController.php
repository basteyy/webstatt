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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShowTermsController extends Controller
{
    public UserRole $minimum_user_role = UserRole::ANONYMOUS;

    use TermTrait;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->render('Webstatt::terms/show', [
            'terms' => $this->getParsedTermContent()
        ]);
    }

}