<?php

namespace App\Repository;

use App\Entity\TradeMemberParticipation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TradeMemberParticipation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeMemberParticipation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeMemberParticipation[]    findAll()
 * @method TradeMemberParticipation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeMemberParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TradeMemberParticipation::class);
    }

    // /**
    //  * @return TradeMemberParticipation[] Returns an array of TradeMemberParticipation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TradeMemberParticipation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
