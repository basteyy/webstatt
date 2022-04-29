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
use basteyy\Webstatt\Enums\UserRole;
use basteyy\Webstatt\Models\Entities\EntityInterface;
use basteyy\Webstatt\Models\Entities\InvitationEntity;
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
        return $this->_findBy([['invitationType', '=', InvitationType::LINK->value], ['active', '=', true]]);
    }

    public function findBySecretKey(string $secretKey, bool $include_joining_user_entities = false): null|InvitationEntity
    {
        $result = $this->getRaw()->findOneBy([
            'secretKey', '=', $secretKey
        ]);

        if ($include_joining_user_entities && isset($result['usedByUsers'])) {
            /** @var UsersModel $usersModel */
            $usersModel = $this->getModel(UsersModel::class);
            $userEntities = [];
            foreach ($result['usedByUsers'] as $value) {
                $user = $usersModel->findById($value['userId']);
                if ($user) {
                    $userEntities[] = $user;
                } else {
                    //$userEntities[] = $value;
                    $result['deletedUsedByUsers'] = $value;
                }
            }

            $result['usedByUsers'] = $userEntities;
        }

        return $this->createEntities($result);
    }

    public function findByPublicKey(string $publicKey): null|InvitationEntity
    {
        return $this->_findByOneArgument('publicKey', '=', $publicKey);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function create(array $entity_data): EntityInterface
    {
        if (!isset($entity_data['userRole'])) {
            $entity_data['userRole'] = UserRole::USER->value;
        }

        return parent::create($entity_data);
    }

}