<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

readonly class ScoreboardService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param FormInterface $form
     * @param Game $game
     *
     * @return void
     */
    public function addAction(FormInterface $form, Game $game): void
    {
        /** @var Game $data */
        $data = $form->getData();
        $game->setHomeTeam($data->getHomeTeam());
        $game->setAwayTeam($data->getAwayTeam());
        $game->setStartTime(new \DateTime());

        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }

    /**
     * @param Game $game
     *
     * @return void
     */
    public function updateAction(Game $game): void
    {
        $game->setUpdateTime(new \DateTime());
        $this->entityManager->flush();
    }
}
