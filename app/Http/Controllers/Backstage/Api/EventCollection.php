<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EventCollection extends Controller
{
    public function get(Request $request)
    {
        $availableSports = $request->query->get('SelectSport', []);

        $appKey = '3b279a7d-7d95-4eda-89cb-3c1f96093fc6';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jsonodds.com/api/odds/all');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:' . $appKey));

        $res = curl_exec($ch);
        $response = json_decode($res, true);

        return collect($response)
            ->filter(function (array $item) use ($availableSports) {
                return !$availableSports || in_array($item['Sport'], $availableSports);
            })
            ->values()
            ->all();
    }
}
