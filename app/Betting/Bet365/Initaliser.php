<?php

namespace App\Betting\Bet365;

use App\Betting\Bet365\Model\Event;
use App\Betting\Bet365\Model\League;
use App\Betting\Bet365\Model\Sport;
use App\Betting\Bet365\Model\Team;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Http;

class Initaliser
{
    private static array $sports = [
        1 => ['name' => 'Soccer', 'enabled' => true],
        13 => ['name' => 'Tennis', 'enabled' => true],
        78 => ['name' => 'Handball', 'enabled' => false],
        17 => ['name' => 'Ice Hockey', 'enabled' => true],
        12 => ['name' => 'American Football', 'enabled' => true],
        83 => ['name' => 'Futsal', 'enabled' => false],
        92 => ['name' => 'Table Tennis', 'enabled' => false],
        8 => ['name' => 'Rugby Union', 'enabled' => false],
        36 => ['name' => 'Australian Rules', 'enabled' => false],
        9 => ['name' => 'Boxing/UFC', 'enabled' => false],
        90 => ['name' => 'Floorball', 'enabled' => false],
        110 => ['name' => 'Water Polo', 'enabled' => false],
        18 => ['name' => 'Basketball', 'enabled' => true],
        91 => ['name' => 'Volleyball', 'enabled' => false],
        16 => ['name' => 'Baseball', 'enabled' => true],
        14 => ['name' => 'Snooker', 'enabled' => false],
        3 => ['name' => 'Cricket', 'enabled' => false],
        15 => ['name' => 'Darts', 'enabled' => false],
        94 => ['name' => 'Badminton', 'enabled' => false],
        19 => ['name' => 'Rugby League', 'enabled' => false],
        66 => ['name' => 'Bowls', 'enabled' => false],
        75 => ['name' => 'Gaelic Sports', 'enabled' => false],
        95 => ['name' => 'Beach Volleyball', 'enabled' => false],
        107 => ['name' => 'Squash', 'enabled' => false],
    ];

    private string $token;
    private EntityManager $entityManager;

    public function __construct(string $token, EntityManager $entityManager)
    {
        $this->token = $token;
        $this->entityManager = $entityManager;
    }

    public function loadSports()
    {
        foreach (self::$sports as $id => $sport) {
            $sport = new Sport($id, $sport['name'], $sport['enabled']);
            $this->entityManager->persist($sport);
        }

        $this->entityManager->flush();
    }

    public function loadOneLeague(string $sportId)
    {
        /** @var Sport $sport */
        $sport = $this->entityManager->find(Sport::class, $sportId);

        if ($sport === null) {
            return;
        }

        $this->loadSportLeague($sport);
    }

    public function loadAllLeagues()
    {
        /** @var Sport[] $sportEntities */
        $sportEntities = $this->entityManager->getRepository(Sport::class)->findBy(['enabled' => true]);

        foreach ($sportEntities as $sport) {
            $this->loadSportLeague($sport);
        }
    }

    public function loadAllEvents()
    {
        /** @var League[] $leagues */
        $leagues = $this->entityManager->getRepository(League::class)->findBy(['enabled' => true]);

        foreach ($leagues as $league) {
            $this->loadLeagueEvents($league);
        }
    }

    private function loadSportLeague(Sport $sport): void
    {
        $page = 1;
        $leagues = [];
        do {
            $response = Http::get('https://api.betsapi.com/v1/bet365/upcoming', [
                'token' => $this->token,
                'sport_id' => $sport->getId(),
                'page' => $page,
            ]);

            $data = $response->json();

            foreach ($data['results'] as $datum) {
                $league = $datum['league'];
                if (!isset($leagues[$league['id']])) {

                    $leagueEntity = $this->entityManager->find(League::class, $league['id']);

                    if ($leagueEntity !== null) {
                        $leagues[$league['id']] = $leagueEntity;
                        continue;
                    }

                    $leagues[$league['id']] = new League($league['id'], $league['name'], $sport);
                    $this->entityManager->persist($leagues[$league['id']]);
                }
            }

            $page++;

        } while ($this->morePages($data['pager']));

        $this->entityManager->flush();
    }

    private function loadLeagueEvents(League $league)
    {
        $page = 1;
        $sport = $league->getSport();
        $teams = [];

        do {
            $response = Http::get('https://api.betsapi.com/v1/bet365/upcoming', [
                'token' => $this->token,
                'sport_id' => $sport->getId(),
                'page' => $page,
                'league_id' => $league->getId()
            ]);

            $data = $response->json();

            foreach ($data['results'] as $datum) {

                $event = $this->entityManager->find(Event::class, $datum['id']);

                if ($event !== null) {
                    continue;
                }

                $home = $this->getTeam($teams, $datum['home']);
                $away = $this->getTeam($teams, $datum['away']);

                $event = new Event($datum['id'], $datum['time'], $league, $home, $away);
                $this->entityManager->persist($event);
            }

            $page++;

        } while ($this->morePages($data['pager']));

        $this->entityManager->flush();
    }

    private function morePages(array $pager): bool
    {
        return $pager['per_page'] * $pager['page'] < $pager['total'];
    }

    private function getTeam(array &$teams, $teamData): Team
    {
        if (isset($teams[$teamData['id']])) {
            $home = $teams[$teamData['id']];
        } else {
            $home = $this->entityManager->find(Team::class, $teamData['id']);
            if ($home === null) {
                $home = new Team($teamData['id'], $teamData['name']);
                $teams[$teamData['id']] = $home;
                $this->entityManager->persist($home);
            }
        }

        return $home;
    }
}
