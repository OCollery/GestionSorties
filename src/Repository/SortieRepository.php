<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Etat;
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

    public function findSortie($campus, $etat, $nom, $debut, $fin, $user, $inscrit, $nonInscrit)
    {

        $qb = $this->createQueryBuilder('s');

        $qb-> select('s')
            ->leftJoin('s.participants', 'participants')
            ->Where('s.organisateur != :userId')
            ->andWhere('s.campus =:idCampus')
            ->andWhere('s.etat!= :idEtat')
            ->andWhere('s.nom LIKE :nomCherche')
            ->andWhere('s.dateHeureDebut BETWEEN :debut AND :fin')
           // ->andWhere('participants IN (:idInscrit)')
            ->setParameter('userId', $user)
            ->setParameter('idCampus', $campus)
            ->setParameter('idEtat', $etat)
            ->setParameter('nomCherche','%'.$nom.'%')
            ->setParameter('debut',$debut)
            ->setParameter('fin',$fin)
           // ->setParameter('idInscrit',$inscrit)


            ->addOrderBy('s.dateHeureDebut', 'ASC');
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
