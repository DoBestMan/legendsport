<?php
namespace App\Http\Controllers\App\Api;

use App\Domain\Tournament as TournamentEntity;
use App\Domain\TournamentEvent;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\DoctrineTournamentTransformer;
use App\Http\Transformers\App\TournamentTransformer;
use App\Models\Tournament;
use App\Tournament\Enums\TournamentState;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class TournamentCollection extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function get()
    {
        $qb = $this->entityManager->getRepository(TournamentEntity::class)->createQueryBuilder('t');
        $qb->join('t.events', 'e')
            ->join('e.apiEvent', 'a')
            ->where($qb->expr()->isNull('t.completedAt'))
            ->orWhere($qb->expr()->gt('t.completedAt', '?1'))
            ->orWhere($qb->expr()->notIn('t.state', '?2'))
            ->setParameters([
                1 => Carbon::now()->subDay(),
                2 => [TournamentState::COMPLETED(), TournamentState::CANCELED()]
            ]);

        $qb->getQuery()->getResult();

        $qb = $this->entityManager->getRepository(TournamentEntity::class)->createQueryBuilder('t');
        $qb->leftJoin('t.players', 'p')
            ->leftJoin('p.user', 'u')
            ->where($qb->expr()->isNull('t.completedAt'))
            ->orWhere($qb->expr()->gt('t.completedAt', '?1'))
            ->orWhere($qb->expr()->notIn('t.state', '?2'))
            ->setParameters([
                1 => Carbon::now()->subDay(),
                2 => [TournamentState::COMPLETED(), TournamentState::CANCELED()]
            ]);

        $tournaments = $qb->getQuery()->getResult();

        return fractal()
            ->collection($tournaments, new DoctrineTournamentTransformer())
            ->toArray();
    }
}
