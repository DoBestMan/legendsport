<?php

namespace App\Domain;

use App\Domain\BetTypes\SpreadHome;
use App\Tournament\Enums\BetStatus;
use Doctrine\ORM\Mapping as ORM;

/**
 * TournamentBetEvents
 *
 * @ORM\Table(name="tournament_bet_events", indexes={@ORM\Index(name="tournament_bet_events_tournament_bet_id_foreign", columns={"tournament_bet_id"}), @ORM\Index(name="tournament_bet_events_tournament_event_id_foreign", columns={"tournament_event_id"})})
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "money_line_away": "App\Domain\BetTypes\MoneyLineAway",
 *     "money_line_home": "App\Domain\BetTypes\MoneyLineHome",
 *     "spread_away": "App\Domain\BetTypes\SpreadAway",
 *     "spread_home": "App\Domain\BetTypes\SpreadHome",
 *     "total_under": "App\Domain\BetTypes\TotalUnder",
 *     "total_over": "App\Domain\BetTypes\TotalOver"
 * })
 */
abstract class TournamentBetEvent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="odd", type="smallint", nullable=false)
     */
    private $odd;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="handicap", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $handicap;

    /**
     * @var TournamentBet
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentBet", inversedBy="events")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tournament_bet_id", referencedColumnName="id")
     * })
     */
    private $tournamentBet;

    /**
     * @var TournamentEvent
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentEvent", inversedBy="bets")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tournament_event_id", referencedColumnName="id")
     * })
     */
    private $tournamentEvent;

    public function getId(): int
    {
        return $this->id;
    }

    public function getOdd(): int
    {
        return $this->odd;
    }

    public function getStatus(): BetStatus
    {
        return new BetStatus($this->status);
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

    public function evaluate(): void
    {
        if (!$this->getStatus()->equals(BetStatus::PENDING())) {
            return;
        }

        $eventData = $this->getTournamentEvent()->getApiEvent();

        if (!$eventData->isFinished()) {
            return;
        }

        if ($eventData->isCancelled()) {
            $this->result(BetStatus::PUSH());
            return;
        }

        echo get_class($this) . "\n";

        $this->evaluateType();
    }

    protected function result(BetStatus $status)
    {
        $this->status = $status->getValue();
        $this->getTournamentBet()->evaluate();
    }

    public function isPending(): bool
    {
        return $this->getStatus()->equals(BetStatus::PENDING());
    }

    public function isLoss(): bool
    {
        return $this->getStatus()->equals(BetStatus::LOSS());
    }

    public function isWin(): bool
    {
        return $this->getStatus()->equals(BetStatus::WIN());
    }

    public function isPush(): bool
    {
        return $this->getStatus()->equals(BetStatus::PUSH());
    }
}
