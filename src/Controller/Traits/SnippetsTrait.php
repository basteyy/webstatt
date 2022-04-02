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

use basteyy\Webstatt\Helper\FlashMessages;
use basteyy\Webstatt\Models\Entities\SnippetEntity;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

trait SnippetsTrait {

    use ModelTrait;

    protected function checkSnippet(string $secret) : false|SnippetEntity {

        $snippet = $this->getSnippetsModel()->findOneBySecret($secret);

        return $snippet ?? false;

    }

    protected function isValidUpdateData(array $data, SnippetEntity $entity) : bool {

        $valid = true;

        // Secret?
        if($data['secret'] !== $entity->getSecret() && null !== $this->getSnippetsModel()->findOneByKey($data['secret']) ) {
            FlashMessages::addErrorMessage(__('Secret %s already taken', $data['secret']));
            $valid = false;
        }

        // Valid Key?
        if(!ctype_alnum($data['key'])) {
            FlashMessages::addErrorMessage(__('Invalid characters in the key'));
            $valid = false;
        }

        return $valid;
    }

}