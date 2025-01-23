<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    // src/Repository/LogRepository.php

    public function countActionsByUsuario()
    {
        return $this->createQueryBuilder('l')
            ->select('l.Usuario as usuario, COUNT(l.id) as total')
            ->groupBy('l.Usuario')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countActionsByAccion()
    {
        return $this->createQueryBuilder('l')
            ->select('l.Accion as accion, COUNT(l.id) as total')
            ->groupBy('l.Accion')
            ->orderBy('total', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //    /**
    //     * @return Log[] Returns an array of Log objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Log
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
