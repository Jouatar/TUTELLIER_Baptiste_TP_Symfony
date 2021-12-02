<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie", name="category")
     */
    public function index(CategoryRepository $categoryRepository, ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $listCategory = $categoryRepository->findAll();
        //dd($listCategory);
        $essai=$articleRepository->countArticleOfCategory($listCategory[0]->getId());
        dd($essai);
        $categories = $paginator->paginate(
            $listCategory, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'listCategory' => $categories
        ]);
    }
    
    /**
     * @Route("/categorie/{id}", name="category.show")
     */
    public function show($id): Response
    {
        return $this->render('category/show.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
