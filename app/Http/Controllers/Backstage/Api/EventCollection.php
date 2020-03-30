<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;
use App\Services\OddService;

class EventCollection extends Controller
{
    public function get(OddService $oddService)
    {
        return $oddService->getOdds();
    }
}
