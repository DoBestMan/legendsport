<?php

namespace App\Domain;

use App\Tournament\Enums\BetStatus;
use App\Tournament\Enums\PendingOddType;
use Carbon\Carbon;
use Decimal\Decimal;
use Doctrine\ORM\Mapping as ORM;

/**
 * TournamentBetEvents
 *
 * @ORM\Table(name="tournament_bet_events", indexes={@ORM\Index(name="tournament_bet_events_tournament_bet_id_foreign", columns={"tournament_bet_id"}), @ORM\Index(name="tournament_bet_events_tournament_event_id_foreign", columns={"tournament_event_id"})})
 * @ORM\Entity
 */
class TournamentBetEvent
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(type="string") */
    private string $externalId;
    /** @ORM\Column(type="string") */
    private $type;
    /** @ORM\Column(name="odd", type="smallint", nullable=false) */
    private int $odd;
    /** @ORM\Column(name="status", type=BetStatus::class, length=255, nullable=false) */
    private BetStatus $status;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /** @ORM\Column(name="handicap", type="DecimalObject", nullable=true) */
    private ?Decimal $handicap;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentBet", inversedBy="events")
     * @ORM\JoinColumn(name="tournament_bet_id", referencedColumnName="id")
     */
    private TournamentBet $tournamentBet;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentEvent", inversedBy="bets")
     * @ORM\JoinColumn(name="tournament_event_id", referencedColumnName="id")
     */
    private TournamentEvent $tournamentEvent;

    public function __construct(string $type, TournamentEvent $tournamentEvent, TournamentPlayer $tournamentPlayer)
    {
        $odds = $tournamentEvent->getApiEvent()->getOdds($type);

        if ($odds === null) {
            throw BetPlacementException::lineSuspended();
        }

        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
        $this->tournamentEvent = $tournamentEvent;
        $this->odd = $odds->getOdds();
        $this->handicap = $odds->getHandicap();
        $this->status = BetStatus::PENDING();
        $this->externalId = $odds->getExternalId();
        $this->type = $type;
        $tournamentEvent->addBet($this, $tournamentPlayer);
    }

    public function addToBet(TournamentBet $tournamentBet)
    {
        $this->tournamentBet = $tournamentBet;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getOdd(): int
    {
        return $this->odd;
    }

    public function getStatus(): BetStatus
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getHandicap(): ?string
    {
        return $this->handicap;
    }

    public function getTournamentBet(): TournamentBet
    {
        return $this->tournamentBet;
    }

    public function getTournamentEvent(): TournamentEvent
    {
        return $this->tournamentEvent;
    }

    public function evaluate(): bool
    {
        if (!$this->status->equals(BetStatus::PENDING())) {
            return false;
        }

        $eventData = $this->tournamentEvent->getApiEvent();

        if ($eventData->isCancelled()) {
            return $this->result(BetStatus::PUSH());
        }

        $eventData = $this->getTournamentEvent()->getApiEvent();
        $line = $eventData->getLine($this->getExternalId());
        $settlement = $line->getSettlement();

        if ($settlement === null) {
            return false;
        }

        return $this->result($settlement->toBetStatus());
    }

    protected function result(BetStatus $status): bool
    {
        $this->status = $status;
        $this->updatedAt = Carbon::now();
        $this->tournamentEvent->betGraded($this->getTournamentBet()->getTournamentPlayer()->getUser() instanceof Bot);
        return $this->tournamentBet->evaluate();
    }

    public function isPending(): bool
    {
        return $this->status->equals(BetStatus::PENDING());
    }

    public function isLoss(): bool
    {
        return $this->status->equals(BetStatus::LOSS());
    }

    public function isWin(): bool
    {
        return $this->status->equals(BetStatus::WIN());
    }

    public function isPush(): bool
    {
        return $this->status->equals(BetStatus::PUSH());
    }

    public function getSelectedTeam(): ?string
    {
        switch ($this->getType()) {
            case PendingOddType::MONEY_LINE_HOME():
            case PendingOddType::SPREAD_HOME():
                return $this->tournamentEvent->getApiEvent()->getTeamHome();

            case PendingOddType::MONEY_LINE_AWAY():
            case PendingOddType::SPREAD_AWAY():
                return $this->tournamentEvent->getApiEvent()->getTeamAway();

            default:
                return null;
            }
    }
}
