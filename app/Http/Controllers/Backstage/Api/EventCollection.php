<?php
namespace App\Http\Controllers\Backstage\Api;

use App\Http\Controllers\Controller;

class EventCollection extends Controller
{
    public function get()
    {
        $appKey = '3b279a7d-7d95-4eda-89cb-3c1f96093fc6';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://jsonodds.com/api/odds/all');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:' . $appKey));

        $res = curl_exec($ch);
        return json_decode($res, true);
    }
}
