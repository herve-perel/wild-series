<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;


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
        return $this->render('category/show.html.twig', ['category' => $category ,'programs' => $programs]);
    }
}
