<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article.index")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        dd($articles);
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'listArticles' => 'articles',
        ]);
    }
}
