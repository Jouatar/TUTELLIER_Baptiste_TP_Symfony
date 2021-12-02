<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article.index")
     */
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $listArticles = $articleRepository->findAll();
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
     * @Route("/article/{id}", name="article.show")
     */
    public function show(ArticleRepository $articleRepository, $id): Response
    {
        $article = $articleRepository->find($id);
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
