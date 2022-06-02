<?php

namespace App\Controller\Customer;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('handleSearch'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    // 'class' => '',
                    'placeholder' => 'Recherchez une recette, un produit...'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'fas fa-search',
                    
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

     
    #[Route('/handleSearch', name:'handleSearch')]
    public function handleSearch(Request $request, 
                                RecipeRepository $recipeRepository, 
                                ProductRepository $productRepository)
    {
        $query = $request->request->get('form')['query'];

        if($query) {
            $recipes = $recipeRepository->findRecipesByName($query);
            $products = $productRepository->findProductsByName($query);
        }

        return $this->render('search/index.html.twig', [
            'recipes' => $recipes,
            'products' => $products
            
        ]);
    }
}
