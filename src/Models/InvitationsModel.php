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

namespace basteyy\Webstatt\Models;


use basteyy\Webstatt\Enums\InvitationType;
use basteyy\Webstatt\Models\Entities\EntityInterface;
use Exception;
use ReflectionException;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use function basteyy\VariousPhpSnippets\getRandomString;
use function basteyy\VariousPhpSnippets\varDebug;

final class InvitationsModel extends Model
{

    protected string $database_name = 'invitations';


    /**
     * @return array|EntityInterface|null
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws InvalidConfigurationException
     * @throws ReflectionException
     */
    public function getActiveInvitationLinks(): array|null|EntityInterface
    {
        return $this->_findBy([
                ['invitationType', '=', InvitationType::LINK], 'AND', ['active', '=', true]
            ]
        );
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(array $entity_data): EntityInterface
    {
        if (!isset($entity_data['secret_key'])) {
            $entity_data['secret_key'] = getRandomString(32);
        }

        return parent::create($entity_data);
    }

}