<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Form\GameUpdateType;
use App\Repository\GameRepository;
use App\Service\ScoreboardService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreboardController extends AbstractController
{
    /**
     * @param GameRepository $gameRepository
     *
     * @return Response
     */
    #[Route('/', name: 'app_view_action')]
    public function viewAction(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy(['endTime' => null]);

        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }

    /**
     * @param Request $request
     * @param ScoreboardService $scoreboardService
     *
     * @return Response
     */
    #[Route('/add-event', name: 'app_add_action')]
    public function addAction(Request $request, ScoreboardService $scoreboardService): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scoreboardService->addAction($form, $game);

            $this->addFlash('success', 'Match added.');

            return $this->redirectToRoute('app_view_action');
        }

        return $this->render('game/add.html.twig', [
            'formAdd' => $form->createView(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/summary', name: 'app_summary_action')]
    public function summaryAction(GameRepository $gameRepository): Response
    {
        return $this->render('game/summary.html.twig', [
            'games' => $gameRepository->games(),
        ]);
    }

    /**
     * @param Request $request
     * @param Game $game
     * @param ScoreboardService $scoreboardService
     *
     * @return Response
     */
    #[Route('/update/{id}', name: 'app_update_action')]
    public function updateAction(Request $request, Game $game, ScoreboardService $scoreboardService): Response
    {
        $form = $this->createForm(GameUpdateType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scoreboardService->updateAction($game);

            $this->addFlash('success', 'Updated!');

            return $this->redirectToRoute('app_view_action');
        }

        return $this->render('game/update.html.twig', [
            'formUpdate' => $form,
            'game' => $game,
        ]);
    }

    /**
     * @param Game $game
     * @param GameRepository $gameRepository
     *
     * @return Response
     */
    #[Route('/finish/{id}', name: 'app_finish_action')]
    public function finishAction(Game $game, GameRepository $gameRepository): Response
    {
        $gameRepository->finish($game->getId());

        $this->addFlash('success', 'Game finished');

        return $this->redirectToRoute('app_summary_action');
    }
}
