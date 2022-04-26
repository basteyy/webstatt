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

namespace basteyy\Webstatt\Models\Entities;

use basteyy\Webstatt\Enums\InvitationType;
use DateTime;
use function basteyy\VariousPhpSnippets\varDebug;

class InvitationEntity extends Entity implements EntityInterface
{
    protected bool $active;
    protected InvitationType $invitationType;
    protected string $acceptanceRules;
    protected int $acceptanceLimit;
    protected DateTime $acceptanceTimeoutDatetime;
    protected int $usedTimes = 0;

    public function getActive() : bool
    {
        return $this->active;
    }

    public function getInvitationType() : InvitationType
    {
        return $this->invitationType;
    }

    public function getAcceptanceRules() : string
    {
        return $this->acceptanceRules;
    }

    public function getAcceptanceLimit() : int
    {
        return $this->acceptanceLimit;
    }

    public function getAcceptanceLimitLeft() : int {
        return $this->acceptanceLimit - $this->usedTimes;
    }

    public function getAcceptanceTimeoutDatetime() : DateTime
    {
        return $this->acceptanceTimeoutDatetime;
    }
}