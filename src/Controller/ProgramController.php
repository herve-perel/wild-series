<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $programs = $programRepository->findAll();
        $seasons = $seasonRepository->findAll();
       
        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs,
            'seasons' => $seasons,
          
            ]
        );
    }

    #[Route('/{id}', methods: ['GET'], requirements: ['page' => '\d+'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }


    #[Route('/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(int $programId, ProgramRepository $programRepository, int $seasonId, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository): Response
    {
        $program = $programRepository->find($programId);
        
        $episodes = $episodeRepository->findAll();
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $programId . ' found in season\'s table.'
            );
        }

        $season = $seasonRepository->find($seasonId);
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $seasonId . ' found in program\'s table.'
            );
        }
        

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season,'episodes' => $episodes ]);
    }
}
