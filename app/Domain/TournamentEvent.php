<?php

namespace App\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tournament_events", indexes={
 *     @ORM\Index(name="tournament_events_api_event_id_foreign", columns={"api_event_id"}),
 *     @ORM\Index(name="tournament_events_tournament_id_foreign", columns={"tournament_id"})
 * })
 * @ORM\Entity
 */
class TournamentEvent
{
    /**
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;
    /** @ORM\Column(name="created_at", type="datetime", nullable=true) */
    private ?\DateTime $createdAt;
    /** @ORM\Column(name="updated_at", type="datetime", nullable=true) */
    private ?\DateTime $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\ApiEvent", inversedBy="tournamentEvents")
     * @ORM\JoinColumn(name="api_event_id", referencedColumnName="id")
     */
    private ApiEvent $apiEvent;
    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Tournament", inversedBy="events")
     * @ORM\JoinColumn(name="tournament_id", referencedColumnName="id")
     */
    private Tournament $tournament;
    /**
     * @var TournamentBetEvent[]
     * @ORM\OneToMany(targetEntity="App\Domain\TournamentBetEvent", mappedBy="tournamentEvent")
     */
    private Collection $bets;
    /** @ORM\Column(type="integer") */
    private int $betsPlaced = 0;
    /** @ORM\Column(type="integer") */
    private int $betsGraded = 0;
    /** @ORM\Column(type="integer", nullable=true) */
    private ?int $botBetsGraded = 0;
    /** @ORM\Column(type="integer", nullable=true) */
    private ?int $botBetsPlaced = 0;

    public function __construct(Tournament $tournament, ApiEvent $apiEvent)
    {
        $this->bets = new ArrayCollection();
        $this->apiEvent = $apiEvent;
        $this->tournament = $tournament;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getApiEvent(): ApiEvent
    {
        return $this->apiEvent;
    }

    public function getTournament(): Tournament
    {
        return $this->tournament;
    }

    /** @return TournamentBetEvent[] */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function getBetsPlaced(): int
    {
        return $this->betsPlaced;
    }

    public function getBetsGraded(): ?int
    {
        return $this->betsGraded;
    }

    public function getBotBetsGraded(): ?int
    {
        return $this->botBetsGraded;
    }

    public function getBotBetsPlaced(): ?int
    {
        return $this->botBetsPlaced;
    }

    public function canBetBePlaced(bool $allowLiveBetting): bool
    {
        return $this->apiEvent->isBettable($allowLiveBetting);
    }

    public function everyBetHasGraded(): bool
    {
        return $this->bets->forAll(fn (int $key, TournamentBetEvent $tournamentBetEvent) => !$tournamentBetEvent->isPending());
    }

    public function addBet(TournamentBetEvent $bet, TournamentPlayer $tournamentPlayer): void
    {
        $this->bets->add($bet);
        if ($tournamentPlayer->getUser() instanceof Bot) {
            $this->botBetsPlaced++;
        } else {
            $this->betsPlaced++;
        }
    }

    public function betGraded(bool $botBet): void
    {
        if ($botBet) {
            $this->botBetsGraded++;
        } else {
            $this->betsGraded++;
        }
    }
}
