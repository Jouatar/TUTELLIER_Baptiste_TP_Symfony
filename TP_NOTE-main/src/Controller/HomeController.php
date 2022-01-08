<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    private $pRepository;
    public function __construct(ProductRepository $pRepository)
    {
        $this->pRepository = $pRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $listProductsDate = $this->pRepository->orderByDate();
        $listProductsDate = array_slice($listProductsDate, 0, 5, true);
        $listProductsPrice = $this->pRepository->orderByPrice();
        $listProductsPrice = array_slice($listProductsPrice, 0, 5, true);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'listProductsPrice' => $listProductsPrice,
            'listProductsDate' => $listProductsDate,
        ]);
    }
}
