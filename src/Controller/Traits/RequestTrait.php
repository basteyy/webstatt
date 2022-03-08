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

use Psr\Http\Message\RequestInterface;
use Slim\Psr7\Request;

/**
 * A varoius collection of methods, which are working with the `Requestinterface`
 * @see RequestInterface
 * @see Request
 */
trait RequestTrait {

    private RequestInterface $request;

    /**
     * Shortcut for checking, if the current Request MEthod ist post
     * @return bool
     */
    protected function isPost(): bool
    {
        return 'POST' === $this->request->getMethod();
    }

}