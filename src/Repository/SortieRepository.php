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
            ->setParameter('idUtilisateur', $idUtilisateur)
            ->andWhere('s.campus =:idcampus')
            ->setParameter('idcampus', $idCampus)
            /*->andWhere('participant_id = :idUtilisateur')
            ->andWhere('participant_id != :idUtilisateur')*/
            ->andWhere('s.etat= :idEtat')
            ->setParameter('idEtat', $idEtat)
            ->andWhere('s.nom LIKE :nomCherche')
            ->setParameter('nomCherche', $nomCherche)
            //->andWhere('=?')
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
