<?php

namespace App\Repository;

use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    /**
     * Recherche par titre uniquement (LIKE)
     *
     * @return Program[]
     */
    public function findLikeName(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche par titre OU nom d’acteur
     *
     * @return Program[]
     */
    public function findByTitleOrActorName(string $term): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.actors', 'a')
            ->andWhere('p.title LIKE :term OR a.name LIKE :term')  // "name" = propriété de Actor
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('p.title', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
