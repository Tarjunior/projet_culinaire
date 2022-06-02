<?php

namespace App\Controller\Customer;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeSweetOneController extends AbstractController
{
    #[Route('/recipe/sweet/one/{id}', name: 'recipe_sweet_one')]
    public function index(int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) 
        {
            return $this->redirectToRoute("recipe_sweet");
         }
        return $this->render('customer/recipe_sweet_one/index.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
