<?php

namespace App\Repository;

use App\Entity\Diet;
use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @extends ServiceEntityRepository<Diet>
 *
 * @method Diet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diet[]    findAll()
 * @method Diet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DietRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Diet::class);
    }

    public function isDiedDuplicated(object $user, string $date)
    {
        $dietRepository = $this->entityManager->getRepository(Diet::class);
        return $dietRepository->findOneBy(['user' => $user, 'date' => $date]);
    }

    public function getDiets($user, $startDate, $endDate)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :user')
            ->andWhere('d.date BETWEEN :startDate AND :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('user', $user)
            ->getQuery()
            ->getArrayResult();
    }
}

