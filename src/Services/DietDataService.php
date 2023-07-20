<?php

namespace App\Services;

use App\Entity\Diet;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class DietDataService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly UserRepository $userRepository
    ) {
    }

    public function saveDiet($validSets)
    {
        foreach ($validSets as $set) {
            $breakfast = $set[R_BRK];
            $dinner = $set[R_DNR];
            $supper = $set[R_SPR];
            $date = $set['date'];
            $kcal = $set[R_BRK]['kcal'] + $set[R_DNR]['kcal'] + $set[R_SPR]['kcal'];
            $userIdentifier = $this->security->getUser()->getUserIdentifier();
            $user = $this->userRepository->getUserByIdentifier($userIdentifier);

            $dietSet = new Diet();
            $dietSet->setBreakfast($breakfast);
            $dietSet->setDinner($dinner);
            $dietSet->setSupper($supper);
            $dietSet->setDate($date);
            $dietSet->setKcal($kcal);
            $dietSet->setUser($user);

            $this->entityManager->persist($dietSet);
            $this->entityManager->flush();
        }
    }
}