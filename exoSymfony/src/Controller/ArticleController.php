<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ArticleType;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{
    private $aRepository;
    public function __construct(ArticleRepository $aRepository)
    {
        $this->aRepository = $aRepository;
    }


    /**
     * @Route("/article", name="article.index")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $listArticles = $this->aRepository->findAll();
        //dd($articles);
        $articles = $paginator->paginate(
            $listArticles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'listArticles' => $articles
        ]);
    }


    /**
     * @Route("/article/new", name="article.create")
     */
    public function create(): Response {
        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        return $this->render('article/create.html.twig', [
            'articleForm' => $articleForm->createView() 
        ]);
    }

    /**
     * @Route("/article/{id}", name="article.show")
     */
    public function show($id): Response
    {
        $article = $this->aRepository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('The article does not exist');
        } else {
            return $this->render('article/show.html.twig', [
                'controller_name' => 'ArticleController',
                'article' => $article
            ]);
        }
    }
}
