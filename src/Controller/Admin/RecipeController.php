<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
// use App\Search\SearchItem;
// use App\Form\SearchItemType;
use App\Services\HandleImage;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/recipe')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'admin_recipe_index', methods: ['GET'])]
    // J'affiche tous mes recettes 
    public function index(RecipeRepository $recipeRepository, 
                          Request $request,
                          PaginatorInterface $paginator): Response
    {
         // Je pagine les pages après avoir récupéré tous mes recettes
        $recipes = $paginator->paginate(
            $recipeRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/new', name: 'admin_recipe_new', methods: ['GET', 'POST'])]
    // Méthode permettant la création d'une nouvelle recette
    public function new(Request $request, 
          EntityManagerInterface $entityManager,
          HandleImage $handleImage): Response
    {
        // j'instancie un nouveau objet recipe
        $recipe = new Recipe();
        
        // Création d'un formulaire à partir de RecipeType
        // Je y injecte l'instance (l'objet) de la classe catégorie précédemment créée
        $form = $this->createForm(RecipeType::class, $recipe);

        // demmande de traitement de la saisie du formulaire
        $form->handleRequest($request);
        
        // si le form est soumis et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $handleImage->save($file,$recipe);
            }

            // indiquer à EntityManager que cette recette devra être enregistré
            $entityManager->persist($recipe);
            // enregistrement de l'objet dans la BDD
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","La recette a bien été ajouté.");
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('admin_recipe_index', [], Response::HTTP_SEE_OTHER);
        }
        // création de la vue du form affiché sur la page indiqué au render
        return $this->renderForm('admin/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_recipe_show', methods: ['GET'])]
    // Méthode permettant de visualiser la recette
    public function show(Recipe $recipe): Response
    {
        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_recipe_edit', methods: ['GET', 'POST'])]
    // Méthode permettant la modification de la recette
    public function edit(Request $request, 
                         Recipe $recipe, 
                         EntityManagerInterface $entityManager,
                         HandleImage $handleImage): Response
    {
        // Récupération de l'image de la recette
        $oldImage = $recipe->getImage();

        // Création d'un formulaire à partir de RecipeType
        // J'y injecte l'instance (l'objet) de la classe Recipe précédemment créée
        $form = $this->createForm(RecipeType::class, $recipe);
        // Je traite les saisies du form
        $form->handleRequest($request);
        // Si le formulaire a été soumis et si les données sont valides
        // Alors je gère l'ajout de la recette en base de données
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $handleImage->edit($file,$recipe,$oldImage);
            }

            // L'entity manager enregistre l'objet en base de données
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","La recette a bien été modifiée.");
            //Je redirige vers la route de mon choix.
            return $this->redirectToRoute('admin_recipe_index', [], Response::HTTP_SEE_OTHER);
        }
        //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->renderForm('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_recipe_delete', methods: ['POST'])]
    // Méthode permettant la suppression d'une recette
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        // Si le Csrf token est valide
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            
            // Remove permet d'indiquer la suppression de la recette
            $entityManager->remove($recipe);
            // flush supprime la recette
            $entityManager->flush();
            
            //J'envoie un message flash
            $this->addFlash("success","La recette a bien été supprimée.");
        }
        //Je redirige vers la route de mon choix.
        return $this->redirectToRoute('admin_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
