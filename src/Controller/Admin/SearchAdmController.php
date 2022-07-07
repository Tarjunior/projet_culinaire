<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchAdmController extends AbstractController
{
    #[Route('/admin/search_adm', name: 'search_adm')]
    // Méthode responsable de recherches
    public function searchBarAdm()
    {
        // Création d'un form pour les recherches
        $form = $this->createFormBuilder()
            // Définition de l'URL du form
            ->setAction($this->generateUrl('handleSearch_adm'))
            // Ajout d'un champs pour les saisies 
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    // 'class' => '',
                    'placeholder' => 'Recherchez une recette, un produit...'
                ]
            ])
            // Ajout d'un bouton pour l'envoi de la saisie
            ->add('recherche', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'fas fa-search',
                    
                ]
            ])
            ->getForm();
            
        // Affichage du resultat de la recherche
        return $this->render('searchAdm/searchBarAdm.html.twig', [
            'form' => $form->createView()
        ]);
    }

     
    #[Route('/admin/handleSearch_adm', name:'handleSearch_adm')]
    // Méthode pour traiter les recherches/gérer les requêtes
    public function handleSearch(Request $request, 
                                RecipeRepository $recipeRepository, 
                                ProductRepository $productRepository,
                                CategoryRepository $categoryRepository): Response
    {
        // Je récupère la sasie
        $query = $request->request->get('form')['query'];
        // Si elle est true
        if($query) {
            // Je la recherche en BDD 
            $recipes = $recipeRepository->findRecipesByName($query);
            $products = $productRepository->findProductsByName($query);
            $categories = $categoryRepository->findCategoriesByName($query);
        }
        // J'affiche le résultat
        return $this->render('searchAdm/index.html.twig', [
            'recipes' => $recipes,
            'products' => $products,
            'categories' => $categories
            
        ]);
    }
}
