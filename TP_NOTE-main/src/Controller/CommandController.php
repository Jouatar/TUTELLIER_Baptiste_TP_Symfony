<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CommandRepository;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    private $cRepository;
    public function __construct(CommandRepository $cRepository)
    {
        $this->cRepository = $cRepository;
    }


    /**
     * @Route("/command", name="command.index")
     */
    public function index(): Response
    {
        $listCommands = $this->cRepository->findAll();
        //dd($commands);
        return $this->render('command/index.html.twig', [
            'controller_name' => 'CommandController',
            'listCommands' => $listCommands
        ]);
    }

    /**
     * @Route("/command/{id}", name="command.show")
     */
    public function show($id): Response
    {
        $command = $this->cRepository->find($id);
        if (!$command) {
            throw $this->createNotFoundException('The command does not exist');
        } else {
            return $this->render('command/show.html.twig', [
                'controller_name' => 'commandController',
                'command' => $command,
                'listProducts' => $command->getProducts()
            ]);
        }
    }
}
