<?php

namespace App\Http\Controllers\Backstage\View;

use App\Betting\LegendsOdds\LegendsOdds;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use App\Http\Controllers\Controller;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;

class BookController extends Controller
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function active()
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('provider', LegendsOdds::PROVIDER_NAME))
            ->andWhere(Criteria::expr()->in('timeStatus', [TimeStatus::NOT_STARTED(), TimeStatus::IN_PLAY()]))
            ->orderBy(['startsAt' => 'ASC']);


        $repository = $this->entityManager->getRepository(ApiEvent::class);
        $events = $repository->matching($criteria);

        return view('backstage.book.active')
            ->with('events', $events);
    }

    public function cancel($id)
    {
        return $id;
    }

    public function finish($id)
    {
        return $id;
    }

    public function start($id)
    {
        return $id;
    }
}
