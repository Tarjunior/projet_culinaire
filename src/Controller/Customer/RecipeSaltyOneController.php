<?php

namespace App\Controller\Customer;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeSaltyOneController extends AbstractController
{
    #[Route('/recipe/salty/one/{id}', name: 'recipe_salty_one')]
    public function index(int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) 
        {
           return $this->redirectToRoute("recipe_salty");
        }
        return $this->render('customer/recipe_salty_one/index.html.twig', [
           'recipe' => $recipe
        ]);
    }
}
