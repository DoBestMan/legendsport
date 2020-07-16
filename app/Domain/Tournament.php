<?php

namespace App\Domain;

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
    private int $chips;
    /** @ORM\Column(name="commission", type="integer", nullable=false, options={"unsigned"=true}) */
    private int $commission;
    /** @ORM\Column(name="late_register", type="boolean", nullable=true)*/
    private ?bool $lateRegister;
    /** @ORM\Column(name="late_register_rule", type="json", nullable=true) */
    private array $lateRegisterRule;
    /** @ORM\Column(name="prize_pool", type="json", nullable=false) */
    private array $prizePool;
    /** @ORM\Column(name="state", type="string", length=255, nullable=false) */
    private string $state;
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
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentEvent", mappedBy="tournament") */
    private Collection $events;
    /** @ORM\OneToMany(targetEntity="\App\Domain\TournamentBet", mappedBy="tournament", cascade={"ALL"}) */
    private Collection $bets;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->bets = new ArrayCollection();
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

    public function getState(): string
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

    public function getEvents(): Collection
    {
        return new ArrayCollection($this->events->toArray());
    }

    public function placeStraightBet(TournamentPlayer $tournamentPlayer, TournamentEvent $event, string $betType, int $wager): void
    {
        $tournamentPlayer->reduceChips($wager);
        $bet = new TournamentBet($this, $tournamentPlayer, $wager);
        new $betType($event, $bet, $event->getApiEvent()->getOdds($betType));

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

    public function registerBot(Bot $bot)
    {
        $this->botsRegistered++;
        $this->players->add(
            new TournamentPlayer($this, $bot, $this->chips)
        );
    }
}
