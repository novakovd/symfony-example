<?php

namespace App\Repository\Example;

use App\Entity\Example\Duck;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @method Duck|null find($id, $lockMode = null, $lockVersion = null)
 * @method Duck|null findOneBy(array $criteria, array $orderBy = null)
 * @method Duck[]    findAll()
 * @method Duck[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DuckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Duck::class);
    }
}
