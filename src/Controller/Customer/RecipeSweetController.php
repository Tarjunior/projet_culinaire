<?php

namespace App\Controller\Customer;

use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeSweetController extends AbstractController
{
    #[Route('/recipe/sweet', name: 'recipe_sweet')]
    public function index(RecipeRepository $recipeRepository, 
                          PaginatorInterface $paginator, 
                          Request $request): Response
    {
        $recipes = $paginator->paginate(
            $recipeRepository->findBy(['type' => 'Sucrée'],['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
    
        return $this->render('customer/recipe_sweet/index.html.twig', [
            'recipes' => $recipes
        ]);
    }
}
