<?php

namespace App\Http\Controllers\Backstage\View;

use App\Betting\LegendsOdds;
use App\Betting\TimeStatus;
use App\Domain\ApiEvent;
use App\Http\Controllers\Controller;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookController extends Controller
{
    private EntityManager $entityManager;
    private string $baseUrl;
    private string $authToken;

    public function __construct(EntityManager $entityManager, string $baseUrl, string $authToken)
    {
        $this->entityManager = $entityManager;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->authToken = $authToken;
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
        $entity = $this->entityManager->getRepository(ApiEvent::class)->find($id);

        if (!($entity instanceof ApiEvent)) {
            return false;
        }

        $url = sprintf('%s/api/v1/manual-override/%s/cancel?authtoken=%s', $this->baseUrl, $entity->getApiId(), $this->authToken);
        $response = Http::post($url);

        if ($response->failed()) {
            throw new \RuntimeException('Unable to communicate with API');
        }

        return $response;
    }

    public function finish($id, Request $request)
    {
        $this->validate($request, [
            'homeScore' => 'required|numeric|min:0',
            'awayScore' => 'required|numeric|min:0'
        ]);

        $entity = $this->entityManager->getRepository(ApiEvent::class)->find($id);

        if (!($entity instanceof ApiEvent)) {
            return false;
        }

        $url = sprintf('%s/api/v1/manual-override/%s/finish?authtoken=%s', $this->baseUrl, $entity->getApiId(), $this->authToken);
        $response = Http::post($url, ['homeScore' => $request->homeScore, 'awayScore' => $request->awayScore]);

        if ($response->failed()) {
            throw new \RuntimeException('Unable to communicate with API');
        }

        return $response;
    }

    public function start($id)
    {
        $entity = $this->entityManager->getRepository(ApiEvent::class)->find($id);

        if (!($entity instanceof ApiEvent)) {
            return false;
        }

        $url = sprintf('%s/api/v1/manual-override/%s/start?authtoken=%s', $this->baseUrl, $entity->getApiId(), $this->authToken);
        $response = Http::post($url);

        if ($response->failed()) {
            throw new \RuntimeException('Unable to communicate with API');
        }

        return $response;
    }
}
