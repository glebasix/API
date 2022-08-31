<?php

namespace App\Repository;

use App\Entity\BtcCourses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BtcCourses>
 *
 * @method BtcCourses|null find($id, $lockMode = null, $lockVersion = null)
 * @method BtcCourses|null findOneBy(array $criteria, array $orderBy = null)
 * @method BtcCourses[]    findAll()
 * @method BtcCourses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BtcCoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BtcCourses::class);
    }

    public function add(BtcCourses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BtcCourses $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByDateField($start, $end)
    {
        return $this->createQueryBuilder('b')
            ->andwhere('b.dataTime >= :start')
            ->andWhere('b.dataTime <= :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCurrencyField($currency)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.currency = :val')
            ->setParameter('val', $currency)
            ->getQuery()
            ->getResult()
       ;
    }


//    /**
//     * @return BtcCourses[] Returns an array of BtcCourses objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//
//    public function findOneBySomeField($value): ?BtcCourses
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
