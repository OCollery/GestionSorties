<?php

namespace App\Repository;

use App\Entity\ParticipantFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ParticipantFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantFile[]    findAll()
 * @method ParticipantFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantFile::class);
    }

    // /**
    //  * @return ParticipantFile[] Returns an array of ParticipantFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ParticipantFile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
