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
use basteyy\Webstatt\Models\Entities\InvitationEntity;
use basteyy\Webstatt\Models\Entities\UserEntity;
use basteyy\Webstatt\Models\InvitationsModel;
use Exception;
use Psr\Http\Message\ResponseInterface;
use SleekDB\Exceptions\InvalidArgumentException;
use SleekDB\Exceptions\InvalidConfigurationException;
use SleekDB\Exceptions\IOException;
use SleekDB\Exceptions\JsonException;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\getDateTimeFormat;
use function basteyy\VariousPhpSnippets\getRequestIpAddress;
use function basteyy\VariousPhpSnippets\varDebug;

trait InvitationsTrait
{

    /** @var InvitationsModel $invitationsModel */
    private InvitationsModel $invitationsModel;

    /**
     * Increase the used times by one or more
     * @throws IOException
     * @throws JsonException
     * @throws InvalidConfigurationException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    protected function increaseUsedTimes(InvitationEntity $invitationEntity, int $increasing_number = 1): void
    {
        if ($increasing_number < 1) {
            throw new Exception(__('You can not increase with a negative number'));
        }

        $this->getInvitationModel()->patch($invitationEntity, [
            'usedTimes' => $invitationEntity->getUsedTimes() + $increasing_number
        ]);
    }


    protected function addInvitedUserId (InvitationEntity $invitationEntity, UserEntity $userEntity): void {

        $array = $invitationEntity->getUsedUsers();

        $array[] = [
            'userId' => $userEntity->getId(),
            'dateTime' => getDateTimeFormat(),
            'getUserIp' => getRequestIpAddress()
        ];

        $this->getInvitationModel()->patch($invitationEntity, [
            'usedByUsers' => $array
        ]);
    }

    /**
     * Wrapper for the Model
     * @return InvitationsModel
     */
    protected function getInvitationModel(): InvitationsModel
    {
        if (!isset($this->invitationsModel)) {
            $this->invitationsModel = $this->getModel(InvitationsModel::class);
        }
        return $this->invitationsModel;
    }

    /**
     * Get a invitation by its secret key
     * @param string $secretKey
     * @return InvitationEntity|false
     */
    protected function isValidSecretKey(string $secretKey, bool $include_joining_user_entities = false): InvitationEntity|false
    {
        $result = $this->getInvitationModel()->findBySecretKey($secretKey, $include_joining_user_entities);
        return $result ?? false;
    }

    protected function isValidPublicKey(string $publicKey): InvitationEntity|false
    {
        $result = $this->getInvitationModel()->findByPublicKey($publicKey);
        return $result ?? false;
    }

    /**
     * Handle a invalid entity request
     * @return ResponseInterface
     */
    protected function handleInvalidInvitation(): ResponseInterface
    {
        FlashMessages::addErrorMessage(__('The given invitation was incorrect'));
        return $this->redirect('/admin/users/invite');
    }

}