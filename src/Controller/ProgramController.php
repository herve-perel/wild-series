<?php

namespace App\Controller;



use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, SeasonRepository $seasonRepository, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $session = $requestStack->getSession();
        if (!$session->has('total')) {
            $session->set('total', 0); // if total doesn’t exist in session, it is initialized.
        }
        $total = $session->get('total');

        $programs = $programRepository->findAll();
        $seasons = $seasonRepository->findAll();

        return $this->render(
            'program/index.html.twig',
            [
                'programs' => $programs,
                'seasons' => $seasons,
            ]
        );
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $programRepository->save($program, true);
            $this->addFlash('success', 'The new program has been created');
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{program}/season/{season}', methods: ['GET'], name: 'season_show')]
    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', ['program' => $program]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', methods: ['GET'], name: 'episode_show')]


    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}
