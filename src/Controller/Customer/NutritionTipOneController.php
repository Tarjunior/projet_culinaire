<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NutritionTipOneController extends AbstractController
{
    #[Route('/nutrition/tip/one', name: 'nutrition_tip_one')]
    public function index(): Response
    {
        return $this->render('customer/nutrition_tip_one/index.html.twig', [
            'controller_name' => 'NutritionTipOneController',
        ]);
    }
}
