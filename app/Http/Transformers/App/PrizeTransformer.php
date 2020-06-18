<?php
namespace App\Http\Transformers\App;

use App\Tournament\Prize;
use App\Tournament\PrizeMoney;
use League\Fractal\TransformerAbstract;

class PrizeTransformer extends TransformerAbstract
{
    public function transform(PrizeMoney $prize)
    {
        return [
            "max_position" => $prize->getMaxPosition(),
            "prize" => $prize->getPrizeMoney(),
        ];
    }
}
