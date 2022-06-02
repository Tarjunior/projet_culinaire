<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Search\SearchItem;
use App\Form\SearchItemType;
use App\Services\HandleImage;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'admin_category_index', methods: ['GET'])]
    //Méthode permettant l'affichage des categories
    public function index(CategoryRepository $categoryRepository, 
                          Request $request, 
                          PaginatorInterface $paginator): Response
    {
        // Paginator
        $categories = $paginator->paginate(
            $categoryRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
           6/*limit per page*/
        );

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        
        ]);
        
    }

    #[Route('/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    // Méthode permettant la création d'un nouvelle objet category  
    public function new(Request $request, 
                        EntityManagerInterface $entityManager,
                        HandleImage $handleImage): Response
    {
        // Création d'une instance de la classe catégorie
        $category = new Category();

        // Création d'un formulaire d'un certain type : CategoryType
        // Je y injecte l'instance (l'objet) de la classe catégorie précédemment créée
        $form = $this->createForm(CategoryType::class, $category);

        // Traitement des données (saisies) du formulaire
        $form->handleRequest($request);

        // Si le formulaire a été soumis et si les données sont valides
        // Alors je gère l'ajout de la catégorie en base de données
        if ($form->isSubmitted() && $form->isValid()) {

            //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $handleImage->save($file,$category);
            }

            //L'entity manager persist l'objet catégorie
            //L'entity manager, prépare la catégorie a aller en base de données
            $entityManager->persist($category);

            //L'entity manager envoie pour de bon les données en base.
            $entityManager->flush();

            //Si toutes les étapes sont vqlidées, j'envoie un message flash
            $this->addFlash("success","La catégorie a bien été ajouté.");

            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->renderForm('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    // Méthode permettant la modification de la categorie
    public function edit(Request $request, 
                        Category $category, 
                        EntityManagerInterface $entityManager,
                        HandleImage $handleImage): Response
    {   // Récupération de l'image de la categorie
        $oldImage = $category->getImage();

        // Création d'un formulaire d'un certain type : CategoryType
        // Je y injecte l'instance (l'objet) de la classe catégorie précédemment créée
        $form = $this->createForm(CategoryType::class, $category);

        // Cela permet de traiter les données du formulaire
        $form->handleRequest($request);

         // Si le formulaire a été soumis et si les données sont valides
        // Alors je gère l'ajout de la catégorie en base de données
        if ($form->isSubmitted() && $form->isValid()) {

            //Recuperer le fichier 
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            //Verifier que il y a bien un fichier
            if($file)
            {
                $handleImage->edit($file,$category,$oldImage);
            }

            // L'entity manager persist l'objet catégorie
            // L'entity manager, prépare la catégorie a aller en base de données
            $entityManager->flush();

            // Si toutes les étapes sont validées, j'envoie un message flash
            $this->addFlash("success","La catégorie a bien été modifiée.");
            
            //Je redirige vers la route de mon choix.
            return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

         //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->renderForm('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_category_delete', methods: ['POST'])]
    // Méthode permettant la suppression d'une catégorie
    public function delete(Request $request, 
                          Category $category, 
                          EntityManagerInterface $entityManager): Response
    {
        // Si le Csrf token est valide
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {

            // Remove permet d'indiquer la suppression de la catégorie
            $entityManager->remove($category);

            // flush supprime la catégorie
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","La catégorie a bien été supprimée.");
        }

         //Je redirige vers la route de mon choix.
        return $this->redirectToRoute('admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
