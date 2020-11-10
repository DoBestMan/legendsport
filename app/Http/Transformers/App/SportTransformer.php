<?php
namespace App\Http\Transformers\App;

use App\Betting\SportEvent\Sport;
use League\Fractal\TransformerAbstract;

class SportTransformer extends TransformerAbstract
{
    public function transform(Sport $sport)
    {
        return [
            'id' => $sport->getId(),
            'name' => $sport->getName(),
            'provider' => $sport->getProvider(),
        ];
    }
}
