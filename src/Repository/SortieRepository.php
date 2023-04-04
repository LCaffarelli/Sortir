<?php

namespace App\Repository;

use App\Class\FiltresSorties;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Sortie>
 *
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

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function filtre(FiltresSorties $filtres, User $userCo, $date)
    {
        $now = new \DateTime();
        $dateFiltre = $now->sub(new \DateInterval('P1M'));
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->orderBy('c.nom', 'ASC');

        if (!empty($filtres->Sites)) {
            $queryBuilder->andWhere('c.site = :site')
                ->setParameter('site', $filtres->Sites->getId());
        }

        if (!empty($filtres->textRecherche)) {
            $queryBuilder->andWhere('c.nom LIKE :keywords')
                ->setParameter('keywords', '%' . $filtres->textRecherche . '%');
        }

        if (!empty($filtres->firstDate) && !empty($filtres->secondeDate)) {
            $queryBuilder->andWhere('c.dateHeureDebut BETWEEN :startDate AND :endDate')
                ->setParameter('startDate', $filtres->firstDate)
                ->setParameter('endDate', $filtres->secondeDate);
        }

        if (!empty($filtres->organisateur)) {
            $queryBuilder->andWhere('c.organisateur = :user')
                ->setParameter('user', $userCo->getId());
        }

        if (!empty($filtres->inscrit) && empty($filtres->nonInscrit)) {
            $queryBuilder->andWhere(':user MEMBER OF c.users')
                ->setParameter('user', $userCo);

        }

        if (!empty($filtres->nonInscrit) && empty($filtres->inscrit)) {
            $queryBuilder->andWhere(":user NOT MEMBER OF c.users")
                ->setParameter('user', $userCo);
        }

        if (!empty($filtres->oldSortie)) {
            $queryBuilder->andWhere('c.dateHeureDebut < :date')
                ->setParameter('date', $date);
        }

        $queryBuilder->andWhere('c.dateHeureDebut >= :n30days');
        $queryBuilder->setParameter('n30days', $dateFiltre);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function findAllWithDateLessThanOneMonth(int $id)
    {
        $now = new \DateTime();
        $dateFiltre = $now->sub(new \DateInterval('P1M'));

        $queryBuilder = $this->createQueryBuilder('s');

        $queryBuilder->andWhere('s.dateHeureDebut >= :n30days');
        $queryBuilder->setParameter('n30days', $dateFiltre);
        $queryBuilder->andWhere('s.etat >= 2');
        $queryBuilder->orWhere('s.etat = 1 AND s.organisateur = :user');
        $queryBuilder->setParameter('user', $id);
        $queryBuilder->orderBy('s.dateHeureDebut', 'ASC');
        $query = $queryBuilder->getQuery();

        return $query->getResult();

    }




//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
