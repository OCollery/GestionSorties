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

    public function findSortie($idCampus)
    {

        $qb = $this->createQueryBuilder('s');

        $qb->//Where('s.organisateur = :idUtilisateur')
         //   ->orWhere('s.organisateur != :userId')
            andWhere('s.campus =:idcampus')
         //   ->andWhere('participant_id = :idUtilisateur')
           // ->andWhere('participant_id != :idUtilisateur')
           // ->andWhere('s.etat= :idEtat')
           // ->orWhere('s.etat != 5')
            //->andWhere('s.nom LIKE :nomCherche')
//            ->andWhere('s.dateHeureDebut BETWEEN :debut AND :fin')
  //          ->setParameter('idUtilisateur', $organisateur)
            //->setParameter('userId', $user)
            ->setParameter('idcampus', $idCampus)
   //         ->setParameter('idEtat', $etat)
      //      ->setParameter('nomCherche','%'.$nom.'%')
        //    ->setParameter('debut',$dateDebut)
          //  ->setParameter('fin',$dateFin)

            //    ->join('s.sortie_participant', 'participant')
          //  ->addSelect('participant')
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
