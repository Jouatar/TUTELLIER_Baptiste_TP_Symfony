<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ProductType;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{
    private $pRepository;
    public function __construct(ProductRepository $pRepository)
    {
        $this->pRepository = $pRepository;
    }


    /**
     * @Route("/product", name="product.index")
     */
    public function index(): Response
    {
        $listProducts = $this->pRepository->findAll();
        //dd($products);
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'listProducts' => $listProducts
        ]);
    }

    /**
     * @Route("/product/{id}", name="product.show")
     */
    public function show($id, SessionInterface $session): Response
    {
        $product = $this->pRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        } else {
            // Pour vider le pannier
            //$session->set('panier', []);
            $cart = $session->get('panier', []);
            return $this->render('product/show.html.twig', [
                'controller_name' => 'ProductController',
                'product' => $product,
                'cart' => $cart
            ]);
        }
    }
}
