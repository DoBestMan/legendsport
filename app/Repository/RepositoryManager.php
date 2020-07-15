<?php

namespace App\Repository;

class RepositoryManager
{
    private \Closure $repositoryFactory;

    public function __construct(callable $repositoryFactory)
    {
        if (!($repositoryFactory instanceof \Closure)) {
            $repositoryFactory = \Closure::fromCallable($repositoryFactory);
        }

        $this->repositoryFactory = $repositoryFactory;
    }

    public function get(string $entityClass): Repository
    {
        $repositoryFactory = $this->repositoryFactory;
        return $repositoryFactory($entityClass);
    }
}
