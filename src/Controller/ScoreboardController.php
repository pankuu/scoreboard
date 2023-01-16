<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Form\GameUpdateType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreboardController extends AbstractController
{
    #[Route('/', name: 'app_view_action')]
    public function viewAction(EntityManagerInterface $entityManager): Response
    {
        $games = $entityManager->getRepository(Game::class)->findBy(['endTime' => null]);

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/add-event', name: 'app_add_action')]
    public function addAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Game $data */
            $data = $form->getData();
            $game->setHomeTeam($data->getHomeTeam());
            $game->setAwayTeam($data->getAwayTeam());
            $game->setStartTime(new \DateTime());

            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Match added.');

            return $this->redirectToRoute('app_view_action');
        }

        return $this->render('game/add.html.twig', [
            'formAdd' => $form,
        ]);
    }

    #[Route('/summary', name: 'app_summary_action')]
    public function summaryAction(GameRepository $gameRepository): Response
    {
        return $this->render('game/summary.html.twig', [
            'games' => $gameRepository->games(),
        ]);
    }

    #[Route('/update/{id}', name: 'app_update_action')]
    public function updateAction(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameUpdateType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game->setUpdateTime(new \DateTime());
            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Updated!');

            return $this->redirectToRoute('app_view_action');
        }

        return $this->render('game/update.html.twig', [
            'formUpdate' => $form,
            'game' => $game,
        ]);
    }

    #[Route('/finish/{id}', name: 'app_finish_action')]
    public function finishAction(Game $game, GameRepository $gameRepository): Response
    {
        $gameRepository->finish($game->getId());

        return $this->redirectToRoute('app_summary_action');
    }
}
