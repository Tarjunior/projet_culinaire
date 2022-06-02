<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NutritionTipController extends AbstractController
{
    #[Route('/nutrition/tip', name: 'nutrition_tip')]
    public function index(): Response
    {
        return $this->render('customer/nutrition_tip/index.html.twig', [
            'controller_name' => 'NutritionTipController',
        ]);
    }
}
