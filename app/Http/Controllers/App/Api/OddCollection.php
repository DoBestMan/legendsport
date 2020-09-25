<?php
namespace App\Http\Controllers\App\Api;

use App\Betting\BettingProvider;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use App\Http\Controllers\Controller;
use App\Http\Transformers\App\ApiEventToOdds;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;

class OddCollection extends Controller
{
    public function get(EntityManager $entityManager)
    {
        $entities = $entityManager->getRepository(ApiEvent::class)->matching(
            Criteria::create()->where(Criteria::expr()->in('timeStatus', [TimeStatus::NOT_STARTED(), TimeStatus::IN_PLAY()]))
        );

        return fractal()
            ->collection($entities->toArray(), new ApiEventToOdds())
            ->toArray();
    }
}
