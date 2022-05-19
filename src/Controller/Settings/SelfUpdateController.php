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

        $output = '';

        if ($this->isPost()) {
            /** Sync from github */
            $process = new \Symfony\Component\Process\Process(['composer', 'update', 'basteyy/webstatt', '--no-interaction'], ROOT);
            $process->enableOutput();
            $process->run();

            FlashMessages::addSuccessMessage(__('Self Update executed'));

            $output = $process->getOutput();
        }

        $process = new \Symfony\Component\Process\Process(['composer', 'info'], ROOT);
        $process->enableOutput();
        $process->run();

        $output .= PHP_EOL . PHP_EOL . PHP_EOL . $process->getOutput();


        return $this->render('Webstatt::settings/self_update', ['process' => $output]);
    }
}