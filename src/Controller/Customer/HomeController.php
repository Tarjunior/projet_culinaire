<?php

namespace App\Controller\Customer;

use App\Repository\RecipeRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RecipeRepository $recipeRepository,
                          ProductRepository $productRepository,): Response
    {
        // $recipes = $recipeRepository->findBy([],['type' => 'DESC']);
        $saltyRecipes = $recipeRepository->findBy([
            'type' => 'Salée'
        ],
       [
           'id' => 'DESC'
       ],
        3);

        
        $sweetRecipes = $recipeRepository->findBy([
            'type' => 'Sucrée'
        ],
       [
           'id' => 'DESC'
       ],
        3);

        $products = $productRepository->findBy([],['id' => 'DESC'],3);

        return $this->render('customer/home/index.html.twig', [
            'saltyRecipes' => $saltyRecipes,
            'sweetRecipes' => $sweetRecipes,
            'products' => $products
        ]);
    }
}

