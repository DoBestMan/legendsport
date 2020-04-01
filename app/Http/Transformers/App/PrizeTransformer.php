<?php
namespace App\Http\Transformers\App;

use App\Tournament\Prize;
use League\Fractal\TransformerAbstract;

class PrizeTransformer extends TransformerAbstract
{
    public function transform(Prize $prize)
    {
        return [
            "max_position" => $prize->getMaxPosition(),
            "prize" => $prize->getPrize()->toInt(),
        ];
    }
}
