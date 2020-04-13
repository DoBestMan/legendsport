<?php
namespace App\Http\Transformers\App;

use App\Betting\SportEventResult;
use League\Fractal\TransformerAbstract;

class SportEventResultTransformer extends TransformerAbstract
{
    public function transform(SportEventResult $result)
    {
        return [
            "external_id" => $result->getExternalEventId(),
            "home" => $result->getHome(),
            "away" => $result->getAway(),
        ];
    }
}
