<?php

namespace App\Domain;

use App\Tournament\Enums\BetStatus;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TournamentPlayers
 *
 * @ORM\Table(name="tournament_players", uniqueConstraints={@ORM\UniqueConstraint(name="tournament_id_user_id", columns={"tournament_id", "user_id"})}, indexes={@ORM\Index(name="tournament_players_user_id_foreign", columns={"user_id"}), @ORM\Index(name="IDX_4D41B8AC33D1A3E7", columns={"tournament_id"})})
 * @ORM\Entity
 */
class TournamentPlayer
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="chips", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $chips;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament", inversedBy="players")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private Tournament $tournament;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\User", inversedBy="tournaments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private User $user;
    /**
     * @ORM\OneToMany(targetEntity="App\Domain\TournamentBet", mappedBy="tournamentPlayer")
     */
    private Collection $bets;

    public function __construct(Tournament $tournament, User $user, int $chips)
    {
        $this->chips = $chips;
        $this->tournament = $tournament;
        $this->user = $user;
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
        $user->joinTournament($this);
        $this->bets = new ArrayCollection();
    }

    public function betPlaced(TournamentBet $tournamentBet)
    {
        $this->bets->add($tournamentBet);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getChips(): int
    {
        return $this->chips;
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

    public function placeWager(int $wager)
    {
        if ($wager > $this->chips) {
            throw BetPlacementException::notEnoughChips();
        }

        $this->chips -= $wager;
    }

    public function betWon(int $wager, int $winnings)
    {
        $this->chips += $wager + $winnings;
        $this->recalculateBalances();
    }

    public function betLost(int $wager)
    {
        $this->recalculateBalances();
    }

    public function betPush($wager)
    {
        $this->chips += $wager;
        $this->recalculateBalances();
    }

    private function recalculateBalances(): void
    {
        $pendingBets = $this->bets->filter(fn (TournamentBet $bet) => $bet->getStatus()->equals(BetStatus::PENDING()))->toArray();
        $pendingChips = array_reduce($pendingBets, fn (int $chips, TournamentBet $bet) => $chips + $bet->getChipsWager(), 0);

        $wonBets = $this->bets->filter(fn (TournamentBet $bet) => $bet->getStatus()->equals(BetStatus::WIN()))->toArray();
        $wonChips = array_reduce($wonBets, fn (int $chips, TournamentBet $bet) => $chips + $bet->getChipsWon(), 0);

        $lostBets = $this->bets->filter(fn (TournamentBet $bet) => $bet->getStatus()->equals(BetStatus::LOSS()))->toArray();
        $lostChips = array_reduce($lostBets, fn (int $chips, TournamentBet $bet) => $chips + $bet->getChipsWager(), 0);

        $this->chips = $this->tournament->getChips() - $lostChips - $pendingChips + $wonChips;
    }
    public function getSortedBetsByWin(): Collection
    {
        $iterator = $this->getBets()->getIterator();
        $iterator -> uasort(function (TournamentBet $a, TournamentBet $b) {
            return -$a->getActualChipsWon() <=> -$b->getActualChipsWon();
        });
        return new ArrayCollection(iterator_to_array($iterator));
    }
}
