<?php
namespace App\Betting;

class Pagination
{
    private int $perPage;
    private int $total;
    private array $results;

    public function __construct(array $results, int $total, int $perPage)
    {
        $this->results = $results;
        $this->total = $total;
        $this->perPage = $perPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
