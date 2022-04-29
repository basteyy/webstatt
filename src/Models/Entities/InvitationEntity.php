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
use basteyy\Webstatt\Enums\UserRole;
use Cassandra\Date;
use DateTime;
use JetBrains\PhpStorm\Pure;
use function basteyy\VariousPhpSnippets\__;
use function basteyy\VariousPhpSnippets\varDebug;

class InvitationEntity extends Entity implements EntityInterface
{
    protected bool $active;
    protected InvitationType $invitationType;
    protected string $acceptanceRules;
    protected int $acceptanceLimit;
    protected DateTime $acceptanceTimeoutDatetime;
    protected int $usedTimes = 0;
    protected string $publicKey;
    protected string $secretKey;
    protected UserRole $userRole;
    protected array $usedByUsers = [];

    public function getUsedUsers() : array {
        return $this->usedByUsers ?? [];
    }

    public function getActiveStateBadge() : string {
        return  $this->getActive() ?
            sprintf('<span class="badge rounded-pill bg-success">%s</span>', __('Active') ):
            sprintf('<span class="badge rounded-pill bg-warning">%s</span>', __('Inactive') );
    }

    public function getUsedTimes() : int {
        return $this->usedTimes;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getActive(): bool
    {
        $cur_date = new DateTime('now');
        if($this->getAcceptanceLimitLeft() < 1 || $cur_date > $this->acceptanceTimeoutDatetime ) {
            return false;
        }
        return $this->active;
    }

    public function getRole(): UserRole
    {
        return $this->userRole ?? UserRole::USER;
    }

    #[Pure] public function getInvitationLink(): string
    {
        return '/admin/invitation/' . $this->getPublicKey();
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getInvitationType(): InvitationType
    {
        return $this->invitationType;
    }

    public function getAcceptanceRules(): string
    {
        return $this->acceptanceRules;
    }

    public function getAcceptanceLimit(): int
    {
        return $this->acceptanceLimit;
    }

    public function getAcceptanceLimitLeft(): int
    {
        return $this->acceptanceLimit - $this->usedTimes;
    }

    public function getAcceptanceTimeoutDatetime(): DateTime
    {
        return $this->acceptanceTimeoutDatetime;
    }
}