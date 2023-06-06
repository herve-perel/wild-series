<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ActorRepository $actorRepository): Response
    {
        $actors = $actorRepository->findAll();

        return $this->render(
            'actor/index.html.twig',
            ['actors' => $actors]
        );
    }

     #[Route('/new', name: 'new')]
    public function new(Request $request, ActorRepository $actorRepository): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);
       
        
        if ($form->isSubmitted() && $form->isValid()) {
        
            $actorRepository->save($actor, true);
            
            $this->addFlash('success', 'The new actor has been created');
            return $this->redirectToRoute('actor_index');
        }
        return $this->render('actor/new.html.twig', [
            'actor' => $actor,
            'form' => $form,
        ]);
    }
    
    #[Route('/actor/{id}/', name: 'show')]

    public function show(Actor $actor): Response

    {
        return $this->render('actor/show.html.twig', ['actor' => $actor]);
    }

    #[Route('/{actor}/program/{program}', requirements: ['actor' => '\d+', 'program' => '\d+'], methods: ['GET'], name: 'program_show')]
    public function showActor(Actor $actor, Program $program): Response
    {
        return $this->render('actor/program_show.html.twig', ['actor' => $actor, 'program' => $program]);
    }

   
}