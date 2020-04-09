<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;
use App\Services\OddService;

class EventCollection extends Controller
{
    public function get(OddService $oddService)
    {
        // TODO Some changes need to be made on frontend
        return $oddService->getOdds();
    }
}
