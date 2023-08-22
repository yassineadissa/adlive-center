<?php

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Campaign::class);
    }

    // /**
    //  * @return Campaign[] Returns an array of Campaign objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Campaign
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function changeValidite(Campaign $campaign)
    {
        if ($campaign->getValid()) {
            $campaign->setValid(false);
        } else {
            $campaign->setValid(true);
        }
        $this->entityManager->persist($campaign);
        $this->entityManager->flush();

        return $campaign;
    }

    public function delete(Campaign $campaign)
    {
        $campaign->setDeleted(true);
        $this->entityManager->persist($campaign);
        $this->entityManager->flush();

        return $campaign;
    }


    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countChildCampaigns($id) : ?int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.deleted = false')
            ->andWhere('c.id = :id') // Ici, vous utilisez le paramètre 'id'.
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }



}
