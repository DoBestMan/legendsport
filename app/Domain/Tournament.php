<?php

namespace App\Domain;

use App\Tournament\Enums\TournamentState;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournament
 *
 * @ORM\Table(name="tournaments")
 * @ORM\Entity
 */
class Tournament
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="avatar", type="boolean", nullable=false) */
    private bool $avatar = false;
    /** @ORM\Column(name="name", type="string", length=255, nullable=false) */
    private string $name;
    /** @ORM\Column(name="players_limit", type="string", length=0, nullable=false) */
    private string $playersLimit;
    /** @ORM\Column(name="buy_in", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $buyIn;
    /** @ORM\Column(name="chips", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $chips = 0;
    /** @ORM\Column(name="commission", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $commission;
    /** @ORM\Column(name="late_register", type="boolean", nullable=true)*/
    private ?bool $lateRegister;
    /** @ORM\Column(name="late_register_rule", type="json", nullable=true) */
    private array $lateRegisterRule;
    /** @ORM\Column(name="prize_pool", type="json", nullable=false) */
    private array $prizePool;
    /** @ORM\Column(name="state", type=TournamentState::class, length=255, nullable=false) */
    private TournamentState $state;
    /** @ORM\Column(name="time_frame", type="string", length=0, nullable=false) */
    private string $timeFrame;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /** @ORM\Column(name="registration_deadline", type="datetime", nullable=true) */
    private ?\DateTime $registrationDeadline;
    /** @ORM\Column(name="late_registration_deadline", type="datetime", nullable=true) */
    private ?\DateTime $lateRegistrationDeadline;
    /** @ORM\Column(name="completed_at", type="datetime", nullable=true)*/
    private ?\DateTime $completedAt;
    /** @ORM\Column(name="bots", type="json", nullable=true) */
    private ?array $bots;
    /** @ORM\Column(name="bots_registered", type="integer") */
    private int $botsRegistered = 0;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentPlayer", mappedBy="tournament", fetch="EXTRA_LAZY", cascade={"ALL"}) */
    private Collection $players;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentEvent", mappedBy="tournament", indexBy="id") */
    private Collection $events;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentBet", mappedBy="tournament", cascade={"ALL"}) */
    private Collection $bets;
    private ?Collection $bettableEvents = null;
    /** @ORM\Column(name="auto_end", type="boolean") */
    private bool $autoEnd = true;
    // @TODO add db mapping
    private int $playersRegistered = 0;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->bets = new ArrayCollection();
        $this->state = TournamentState::REGISTERING();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAvatar(): bool
    {
        return $this->avatar;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayersLimit(): string
    {
        return $this->playersLimit;
    }

    public function getBuyIn(): int
    {
        return $this->buyIn;
    }

    public function getChips(): int
    {
        return $this->chips;
    }

    public function getCommission(): int
    {
        return $this->commission;
    }

    public function getLateRegister(): ?bool
    {
        return $this->lateRegister;
    }

    public function getLateRegisterRule(): array
    {
        return $this->lateRegisterRule;
    }

    public function getPrizePool(): array
    {
        return $this->prizePool;
    }

    public function getState(): TournamentState
    {
        return $this->state;
    }

    public function getTimeFrame(): string
    {
        return $this->timeFrame;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getRegistrationDeadline(): ?\DateTime
    {
        return $this->registrationDeadline;
    }

    public function getLateRegistrationDeadline(): ?\DateTime
    {
        return $this->lateRegistrationDeadline;
    }

    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    public function getBots(): ?array
    {
        return $this->bots;
    }

    public function getBotsRegistered(): int
    {
        return $this->botsRegistered;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function shouldAutoEnd(): bool
    {
        return $this->autoEnd;
    }

    public function getEvents(): Collection
    {
        return new ArrayCollection($this->events->toArray());
    }

    public function canBetBePlaced(): bool
    {
        return in_array($this->state, [
            TournamentState::REGISTERING(),
            TournamentState::LATE_REGISTERING(),
            TournamentState::RUNNING(),
        ]);
    }

    public function getBettableEvents(): Collection
    {
        if ($this->bettableEvents === null) {
            $this->bettableEvents = $this->events->filter(function (TournamentEvent $tournamentEvent) {
                return $tournamentEvent->canBetBePlaced();
            });
        }

        return $this->bettableEvents;
    }

    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function placeStraightBet(TournamentPlayer $tournamentPlayer, int $wager, BetItem $betItem): void
    {
        if (!$this->canBetBePlaced()) {
            throw BetPlacementException::tournamentOver();
        }

        if (!$betItem->getEvent()->canBetBePlaced()) {
            throw BetPlacementException::eventStarted();
        }

        if (!$this->events->contains($betItem->getEvent())) {
            throw BetPlacementException::invalidEvent();
        }

        $tournamentPlayer->reduceChips($wager);
        $betEvent = $betItem->makeBetEvent();
        $bet = new TournamentBet($this, $tournamentPlayer, $wager, $betEvent);

        $this->bets->add($bet);
    }

    public function placeParlayBet(TournamentPlayer $tournamentPlayer, int $wager, BetItem ...$betItems): void
    {
        //@TODO test for correlated parlays
        if (count($betItems) < 2) {
            throw new \DomainException('Must be at least 2 bet items to place a parlay');
        }

        $tournamentPlayer->reduceChips($wager);

        $betEvents = [];
        foreach ($betItems as $betItem) {
            $betEvents[] = $betItem->makeBetEvent();
        }

        $bet = new TournamentBet($this, $tournamentPlayer, $wager, ...$betEvents);

        $this->bets->add($bet);
    }

    /**
     * Rules:
     *  - Min = Max = Add = 0: no bots created
     *  - Min > 0: Min bots created
     *  - Add > 0: Additional bots will be created based on player numbers
     *  - Max > 0: Additional bots will stop being created at this number
     */
    public function getBotsToRegister(): int
    {
        $botData = array_merge(
            ['min' => 0, 'max' => 0, 'add' => 0, 'player' => 1],
            (array) $this->bots
        );

        if ($botData['max'] === 0) {
            $botData['max'] = \PHP_INT_MAX;
        }

        if ($this->bots === null || $this->botsRegistered >= $botData['max']) {
            return 0;
        }

        $botsRequired = $botData['min'];

        if ($botData['add'] > 0) {
            $botsPerPlayer = $botData['add'] / max(1, $botData['player']);
            //@TODO when switch to doctrine is complete, maintain a player count as a property so this isn't required
            $playerCount = $this->players->filter(function (TournamentPlayer $player) {
                return !($player->getUser() instanceof Bot);
            })->count();

            $botsRequired += floor($botsPerPlayer * $playerCount);
        }

        if ($botData['max'] > 0) {
            $botsRequired = min($botData['max'], $botsRequired);
        }

        return max(0, $botsRequired - $this->botsRegistered);
    }

    public function registerBot(Bot $bot): void
    {
        $this->botsRegistered++;
        $this->players->add(
            new TournamentPlayer($this, $bot, $this->chips)
        );
    }

    public function registerPlayer(User $player): void
    {
        $this->playersRegistered++;
        $this->players->add(
            new TournamentPlayer($this, $player, $this->chips)
        );
    }

    public function addEvent(ApiEvent $event): void
    {
        $tournamentEvent = new TournamentEvent($this, $event);
        $this->events->add($tournamentEvent);
    }

    public function getEvent($eventId): ?TournamentEvent
    {
        return $this->events->get($eventId) ?: null;
    }
}
