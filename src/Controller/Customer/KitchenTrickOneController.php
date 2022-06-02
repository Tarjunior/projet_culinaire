<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KitchenTrickOneController extends AbstractController
{
    #[Route('/kitchen/trick/one', name: 'kitchen_trick_one')]
    public function index(): Response
    {
        return $this->render('customer/kitchen_trick_one/index.html.twig', [
            'controller_name' => 'KitchenTrickOneController',
        ]);
    }
}
