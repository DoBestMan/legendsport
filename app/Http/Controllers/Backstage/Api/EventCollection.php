<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;
use App\Services\JsonOddApiService;

class EventCollection extends Controller
{
    public function get(JsonOddApiService $jsonOddApiService)
    {
        return $jsonOddApiService->getOdds();
    }
}
