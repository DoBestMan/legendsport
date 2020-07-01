<?php

namespace App\Domain;

use App\Tournament\Enums\BetStatus;
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
     * @ORM\Column(name="chips_wager", type="integer", nullable=false)
     */
    private $chipsWager;

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
     * @var Tournament
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     * })
     */
    private $tournament;

    /**
     * @var TournamentPlayer
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\TournamentPlayer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tournament_player_id", referencedColumnName="id")
     * })
     */
    private $tournamentPlayer;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\TournamentBetEvent", mappedBy="tournamentBet")
     */
    private Collection $events;

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
