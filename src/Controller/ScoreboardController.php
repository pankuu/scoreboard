<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
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
        $games = $entityManager->getRepository(Game::class)->findAll();

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
}
