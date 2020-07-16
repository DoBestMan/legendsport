<?php

namespace App\BotPlayers;

use App\Domain\Bot;
use App\Domain\Tournament;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Faker\Generator;

class BotFinder
{
    private EntityManager $entityManager;
    private Generator $faker;

    public function __construct(EntityManager $entityManager, Generator $faker)
    {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
    }

    public function notInTournament(Tournament $tournament, int $maxBots)
    {
        $in = $this->entityManager->createQueryBuilder();
        $in->select('b.id')
            ->from(Bot::class, 'b')
            ->join('b.tournaments', 'tp')
            ->join('tp.tournament', 't', Expr\Join::WITH, 't.id = ?1');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('bot')
            ->from(Bot::class, 'bot')
            ->where($qb->expr()->notIn('bot.id', $in->getDQL()))
            ->setMaxResults($maxBots)
            ->setParameter(1, $tournament->getId());

        return $qb->getQuery()->execute();
    }

    public function createBots(int $botsToCreate): array
    {
        $bots = [];
        srand(random_int(0, \PHP_INT_MAX));
        for ($i=0; $i<$botsToCreate; $i++) {
            $bot = Bot::create($this->faker->userName);
            $this->entityManager->persist($bot);
            $bots[] = $bot;
        }

        return $bots;
    }

    public function withChipsLeft(Tournament $tournament)
    {
        $in = $this->entityManager->createQueryBuilder();
        $in->select('b.id')
            ->from(Bot::class, 'b')
            ->join('b.tournaments', 'tp', Expr\Join::WITH, 'tp.chips > 100')
            ->join('tp.tournament', 't', Expr\Join::WITH, 't.id = ?1');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('bot')
            ->from(Bot::class, 'bot')
            ->where($qb->expr()->in('bot.id', $in->getDQL()))
            ->setParameter(1, $tournament->getId())
            ->setMaxResults(100);

        return $qb->getQuery()->execute();
    }
}
