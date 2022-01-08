<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Command;
use App\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;

class CartController extends AbstractController
{
    private $pRepository;
    public function __construct(ProductRepository $pRepository)
    {
        $this->pRepository = $pRepository;
    }
    /**
     * @Route("/cart", name="cart")
     */
    public function index(SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $listProducts = $this->pRepository->findAll();
        $cart = $session->get('panier', []);
        $total = 0;
        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);
        foreach($cart as $id => $quantity){
            foreach($listProducts as $product){
                if($product->getId() === $id){
                    $total = $total + $product->getPrice() * $quantity;
                }
            }
            $command->addProduct($this->pRepository->find($id));
        }
        $request = Request::createFromGlobals();
        $commandForm->handleRequest($request);
        if ($commandForm->isSubmitted() && $commandForm->isValid()) {
            $entityManager->persist($command);
            $entityManager->flush();
            $this->addFlash('success', "La commande {$command->getId()} a été enregistré !");
            return $this->redirectToRoute('cart');
            return $this->redirectToRoute('cart', [
                'id' => $command->getId()
            ]);
        }
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'listProducts' => $listProducts,
            'cart' => $cart,
            'total' => $total,
            'deleted' => false,
            'commandForm' => $commandForm->createView()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="cart.add")
     */
    public function add($id, SessionInterface $session)
    {
        $product = $this->pRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('The product does not exist');
        } else {
            $cart = $session->get('panier', []);
            $cart[$id] = 1;
            $session->set('panier', $cart); 
            $this->addFlash('success', "L'article a bien été ajouter au panier !");
            return $this->redirectToRoute('cart');
        }
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     */
    public function delete($id, SessionInterface $session, ManagerRegistry $doctrine)
    {
        $cart = $session->get('panier', []);
        if (!isset($cart[$id])) {
            throw $this->createNotFoundException('The product does not exist');
        } else {
            $cart = $session->get('panier', []);
            $this->addFlash('success', "L'article {$cart[$id]} a bien été supprimé !");
            unset($cart[$id]);
            $session->set('panier', $cart);
            return $this->redirectToRoute('cart');
        }
    }
}
