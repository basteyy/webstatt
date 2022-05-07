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

namespace basteyy\Webstatt\Controller\Layouts;

use basteyy\Webstatt\Enums\UserRole;
use Psr\Http\Message\ResponseInterface;

class EditLayoutController extends \basteyy\Webstatt\Controller\Controller
{
    public UserRole $minimum_user_role = UserRole::USER;

    public function __invoke(): ResponseInterface
    {
        if ($this->isPost()) {


            return $this->reload();
        }

        return $this->adminRender('layouts/edit', [
            'layouts' => []
        ]);
    }

}