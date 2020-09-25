<?php

namespace App\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class InMemoryRepository implements Repository
{
    private $collection;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    public function add($element): void
    {
        $this->collection->add($element);
    }

    public function remove($element): void
    {
        $this->collection->removeElement($element);
    }

    public function get($key)
    {
        return $this->collection->get($key);
    }

    public function matching(Criteria $criteria): Collection
    {
        return $this->collection->matching($criteria);
    }

    public function startTransaction(): void
    {
        return;
    }

    public function commit(): void
    {
        return;
    }
}
