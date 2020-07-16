<?php

namespace App\Domain;

use App\Tournament\Enums\BetStatus;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TournamentBets
 *
 * @ORM\Table(name="tournament_bets", indexes={@ORM\Index(name="tournament_bets_tournament_id_foreign", columns={"tournament_id"}), @ORM\Index(name="tournament_bets_tournament_player_id_foreign", columns={"tournament_player_id"})})
 * @ORM\Entity
 */
class TournamentBet
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="chips_wager", type="integer", nullable=false) */
    private int $chipsWager;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private Tournament $tournament;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentPlayer")
     * @ORM\JoinColumn(name="tournament_player_id", referencedColumnName="id")
     */
    private TournamentPlayer $tournamentPlayer;
    /** @ORM\OneToMany(targetEntity="App\Domain\TournamentBetEvent", mappedBy="tournamentBet", cascade={"ALL"}) */
    private Collection $events;

    public function __construct(Tournament $tournament, TournamentPlayer $tournamentPlayer, int $wager)
    {
        $this->tournament = $tournament;
        $this->tournamentPlayer = $tournamentPlayer;
        $this->chipsWager = $wager;
        $this->events = new ArrayCollection();
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    public function addEvent(TournamentBetEvent $event): void
    {
        $this->events->add($event);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChipsWager(): int
    {
        return $this->chipsWager;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    public function getTournamentPlayer(): TournamentPlayer
    {
        return $this->tournamentPlayer;
    }

    public function evaluate()
    {
        $betStatus = $this->getStatus();
        if ($betStatus->equals(BetStatus::PENDING()) || $betStatus->equals(BetStatus::LOSS())) {
            return;
        }

        $this->refund();

        if ($betStatus->equals(BetStatus::WIN()) ) {
            $this->creditWinnings();
        }
    }

    private function getStatus(): BetStatus
    {
        switch (true) {
            case $this->events->exists(fn(int $key, TournamentBetEvent $event) => $event->isPending()):
                return BetStatus::PENDING();
            case $this->events->exists(fn(int $key, TournamentBetEvent $event) => $event->isLoss()):
                return BetStatus::LOSS();
            case $this->events->forAll(fn(int $key, TournamentBetEvent $event) => $event->isPush()):
                return BetStatus::PUSH();
            default:
                return BetStatus::WIN();
        }
    }

    public function getReducedDecimalOdds()
    {
        $odds = $this->events
            ->filter(fn(TournamentBetEvent $event) => !$event->isPush())
            ->map(fn(TournamentBetEvent $event) => 1 + american_to_decimal($event->getOdd()));

        $multiplier = 1;
        foreach ($odds as $odd) {
            $multiplier *= $odd;
        }

        return $multiplier;
    }

    public function refund(): void
    {
        $this->tournamentPlayer->addChips($this->chipsWager);
    }

    public function creditWinnings(): void
    {
        $this->tournamentPlayer->addChips(intval(($this->getReducedDecimalOdds() - 1) * $this->chipsWager));
    }
}
