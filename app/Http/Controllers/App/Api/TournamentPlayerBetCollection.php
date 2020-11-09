<?php
namespace App\Http\Controllers\App\Api;

use App\Domain\TournamentPlayer as TournamentPlayerEntity;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\DoctrineTournamentPlayerTransformer;
use Doctrine\ORM\EntityManager;

class TournamentPlayerBetCollection extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get($tournamentId, $playerId)
    {
        $qb = $this->entityManager->getRepository(TournamentPlayerEntity::class)->createQueryBuilder('p');
        $qb -> join('p.tournament', 't')
            -> where ('p.id = :playerId')
            -> andWhere ('t.id = :tournamentId')
            ->setParameter('playerId', $playerId)
            ->setParameter('tournamentId', $tournamentId);

        $tournamentPlayer = $qb->getQuery()->getSingleResult();

        return fractal()
            ->item($tournamentPlayer, new DoctrineTournamentPlayerTransformer())
            ->toArray();
    }
}
