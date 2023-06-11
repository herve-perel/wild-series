<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/comment', name: 'app_comment_')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig');
    }

    #[Route('/new/{episode}/{user}', name: 'new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTRIBUTOR')]
    public function new(Request $request, Episode $episode, User $user, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $comment->setAuthor($user);
        $comment->setEpisode($episode);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $commentRepository->save($comment, true);
            $this->addFlash('success', 'Votre commentaire a été posté !');

            return $this->redirectToRoute('program_episode_show', [
                'programSlug' => $episode->getSeason()->getProgram()->getSlug(),
                'seasonNumber' => $episode->getSeason()->getNumber(),
                'episodeSlug' => $episode->getSlug(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, CommentRepository $programRepository): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($this->getUser() !== $comment->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Tu ne peux pas modifier le commentaire d\'un autre utilisateur !');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $programRepository->save($comment, true);
            $this->addFlash('success', 'The comment has been edited.');

            return $this->redirectToRoute('program_episode_show', [
                'program' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
                'seasonNumber' => $comment->getEpisode()->getSeason()->getNumber(),
                'episode' => $comment->getEpisode()->getSlug(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    #[IsGranted('ROLE_CONTRIBUTOR')]
    public function delete(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        if ($this->getUser() !== $comment->getAuthor() && !$this->isGranted('ROLE_ADMIN')) {
            // If not the owner, throws a 403 Access Denied exception
            throw $this->createAccessDeniedException('Tu ne peux pas supprimer le commentaire d\'un autre utilisateur !');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
            $this->addFlash('danger', 'Ton commentaire a été supprimé !');
        }

        return $this->redirectToRoute('program_episode_show', [
            'program' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
            'seasonNumber' => $comment->getEpisode()->getSeason()->getNumber(),
            'episode' => $comment->getEpisode()->getSlug(),
        ], Response::HTTP_SEE_OTHER);
    }
}