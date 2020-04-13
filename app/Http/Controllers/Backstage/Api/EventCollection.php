<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Betting\BettingProvider;
use App\Http\Controllers\Controller;
use App\Http\Transformers\Backstage\SportEventTransformer;
use Illuminate\Http\Request;

class EventCollection extends Controller
{
    public function get(Request $request, BettingProvider $eventsProvider)
    {
        $page = $request->query->get("page", 1);

        $pagination = $eventsProvider->getEvents($page);

        $items = fractal()
            ->collection($pagination->getResults(), new SportEventTransformer())
            ->toArray();

        return [
            "total" => $pagination->getTotal(),
            "per_page" => $pagination->getPerPage(),
            "items" => $items,
        ];
    }
}
