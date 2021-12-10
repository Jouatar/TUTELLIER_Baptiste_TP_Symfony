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

    private $cRepository;
    private $aRepository;

    public function __construct(CategoryRepository $cRepository, ArticleRepository $aRepository)
    {
        $this->cRepository = $cRepository;
        $this->aRepository = $aRepository;
    }

    /**
     * @Route("/categorie", name="category")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $listCategory = $this->cRepository->findAll();
        foreach($listCategory as $myCategory){
            $allNbArticle[$myCategory->getId()] = $this->aRepository->countArticleOfCategory($myCategory->getId())[0]['nbArticle'];
        }
        $categories = $paginator->paginate(
            $listCategory, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'listCategory' => $categories,
            'listNbArticle' => $allNbArticle
        ]);
    }
    
    /**
     * @Route("/categorie/{id}", name="category.show")
     */
    public function show($id, PaginatorInterface $paginator, Request $request): Response
    {
        $category = $this->cRepository->find($id);
        if (!$category) {
            throw $this->createNotFoundException('The category does not exist');
        }
        $articles = $paginator->paginate(
            $category->getArticles(),
            $request->query->getInt('page', 1),
            10
        );
        $nbArticle = $this->aRepository->countArticleOfCategory($category->getId())[0]['nbArticle'];
        return $this->render('category/show.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
            'listArticles' => $articles,
            'nbArticle'=> $nbArticle
        ]);
    }
}
