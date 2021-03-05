<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findSortie()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->Where('s.organisateur = :idUtilisateur')
            ->andWhere('s.campus =:idcampus')
         //   ->andWhere('participant_id = :idUtilisateur')
           // ->andWhere('participant_id != :idUtilisateur')
            ->andWhere('s.etat= :idEtat')
            ->andWhere('s.nom LIKE :nomCherche')
            ->andWhere('s.dateHeureDebut BETWEEN :debut AND :fin')
            ->setParameter('idUtilisateur', [1])
            ->setParameter('idcampus', 1)
            ->setParameter('idEtat', 2)
            ->setParameter('nomCherche','%%')
            ->setParameter('debut','2015-04-03')
            ->setParameter('fin','2018-10-31')

            //    ->join('s.sortie_participant', 'participant')
          //  ->addSelect('participant')
            ->addOrderBy('s.dateHeureDebut', 'DESC');
        $qb->setMaxResults(30);
        $query = $qb->getQuery();
        return new paginator($query);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
