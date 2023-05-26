<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EpisodeRepository;


#[Route('/episode', name: 'episode_')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EpisodeRepository $episodeRepository): Response
    {

        $episodes = $episodeRepository->findAll();
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodes,
        ]);
    }
}
