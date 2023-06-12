<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use App\Form\CategoryType;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categorys = $categoryRepository->findAll();

        return $this->render(
            'category/index.html.twig',
            ['categorys' => $categorys]
        );
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categoryRepository->save($category, true);
            $this->addFlash('success', 'The new category has been created');
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{categoryName}', name: 'show')]
    public function show(CategoryRepository $categoryRepository, string $categoryName, ProgramRepository $programRepository): Response
    {

        $category = $categoryRepository
            ->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for ' . $categoryName
            );
        }
        $programs = $programRepository->findBy(['category' => $category]);
        return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs]);
    }
}
