<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KitchenTrickController extends AbstractController
{
    #[Route('/kitchen/trick', name: 'kitchen_trick')]
    public function index(): Response
    {
        return $this->render('customer/kitchen_trick/index.html.twig', [
            'controller_name' => 'KitchenTrickController',
        ]);
    }
}
