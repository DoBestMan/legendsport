<?php


namespace App\Repository;


use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class OrmRepository implements Repository
{
    private EntityManagerInterface $entityManager;
    private string $entity;
    private bool $inTransaction = false;

    public function __construct(string $entity, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entity = $entity;
    }

    public function add($element): void
    {
        $this->entityManager->persist($element);
    }

    public function remove($element): void
    {
        $this->entityManager->remove($element);
    }

    public function get($key)
    {
        return $this->entityManager->getRepository($this->entity)->find($key);
    }

    public function matching(Criteria $criteria): Collection
    {
        /** @var \Doctrine\ORM\EntityRepository $repository*/
        $repository = $this->entityManager->getRepository($this->entity);
        return $repository->matching($criteria);
    }

    public function startTransaction(): void
    {
        $this->entityManager->beginTransaction();
        $this->inTransaction = true;
    }

    public function commit(): void
    {
        $this->entityManager->flush();
        if ($this->inTransaction) {
            $this->entityManager->commit();
            $this->inTransaction = false;
        }
    }
}
