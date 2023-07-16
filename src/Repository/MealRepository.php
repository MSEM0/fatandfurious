<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meal>
 *
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Meal::class);
    }

    public function getRandomMeal (string $mealType, array $criteria, int $i, int $mealSets) : array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select($mealType)
            ->from(Meal::class, $mealType)
            ->andWhere($mealType . '.type IN (:types)')
            ->setParameter('types', $criteria['type']);
        if ($i === $mealSets) {
            $queryBuilder->andWhere($mealType . '.doublePortion = :doublePortion')
                ->setParameter('doublePortion', false);
        }            $mealType = $queryBuilder->getQuery()->getArrayResult();
        $randomPick = array_rand($mealType);
        return $mealType[$randomPick];
    }
}


