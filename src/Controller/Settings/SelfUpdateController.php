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

namespace basteyy\Webstatt\Controller\Settings;

use basteyy\Webstatt\Controller\Controller;
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Helper\FlashMessages;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function basteyy\VariousPhpSnippets\__;

class SelfUpdateController extends Controller
{
    protected UserRole $minimum_user_role = UserRole::ADMIN;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if ($this->isPost()) {
            /** Sync from github */
            $process = new \Symfony\Component\Process\Process(['composer', 'update basteyy/webstatt'], ROOT);
            $process->enableOutput();
            $process->start();

            while ($process->isRunning()) {
                // waiting for process to finish
            }

            FlashMessages::addSuccessMessage(__('Self Update executed'));

        } else {
            $process = new \Symfony\Component\Process\Process(['composer', 'info'], ROOT);
            $process->enableOutput();
            $process->run();
        }

        return $this->render('Webstatt::settings/self_update', ['process' => $process->getOutput()]);
    }
}