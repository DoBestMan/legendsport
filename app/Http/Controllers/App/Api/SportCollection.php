<?php
namespace App\Http\Controllers\App\Api;

use App\Http\Controllers\Controller;
use App\Services\SportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SportCollection extends Controller
{
    public function get(SportService $sportService)
    {
        return new JsonResponse($sportService->getSports(), Response::HTTP_OK, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        ]);
    }
}
