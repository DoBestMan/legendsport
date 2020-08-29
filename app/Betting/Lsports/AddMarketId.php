<?php

namespace App\Betting\Lsports;


class AddMarketId extends \IteratorIterator
{
    private $marketId;
    public function __construct(\Iterator $iterator, $marketId)
    {
        parent::__construct(new \ArrayIterator($iterator->current()));
        $this->marketId = $marketId;
    }

    public function current()
    {
        $record = $this->getInnerIterator()->current();
        $record['MarketId'] = $this->marketId;
        return $record;
    }
}
