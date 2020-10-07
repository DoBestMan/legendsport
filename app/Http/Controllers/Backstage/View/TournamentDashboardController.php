<?php

namespace App\Http\Controllers\Backstage\View;

use App\Domain\TournamentPayout;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class TournamentDashboardController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index()
    {
        $qb = $this->entityManager->getRepository(TournamentPayout::class)->createQueryBuilder('p');
        $payouts = $qb->where($qb->expr()->gt('p.paidDate', '?1'))
            ->setParameter(1, Carbon::today()->startOfMonth()->subMonth())
            ->getQuery()
            ->execute();

        $buckets = [
            'today' => [Carbon::today(), null],
            'wtd' => [Carbon::today()->startOfWeek(), null],
            'mtd' => [Carbon::today()->startOfMonth(), null],
            'lastMonth' => [Carbon::today()->startOfMonth()->subMonth(), Carbon::today()->startOfMonth()->subMonth()->endOfMonth()]
        ];

        $payoutBuckets = [
            'today' => ['bots' => 0, 'players' => 0],
            'wtd' => ['bots' => 0, 'players' => 0],
            'mtd' => ['bots' => 0, 'players' => 0],
            'lastMonth' => ['bots' => 0, 'players' => 0],
        ];

        foreach ($buckets as $bucketName => $bucket) {
            foreach ($payouts as $payout) {
                if ($bucket[0]->lessThanorEqualTo($payout->getPaidDate()) && ($bucket[1] === null || $bucket[1]->greaterThanOrEqualTo($payout->getPaidDate()))) {
                    $payoutBuckets[$bucketName][$payout->isBot() ? 'bots' : 'players'] += $payout->getPayout();
                }
            }
        }
        return view('backstage.tournaments.dashboard')
            ->with('payoutBuckets', $payoutBuckets);
    }
}
