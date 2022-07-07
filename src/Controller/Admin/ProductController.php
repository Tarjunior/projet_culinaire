<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
// use App\Search\SearchItem;
// use App\Form\SearchItemType;
use App\Services\HandleImage;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'admin_product_index', methods: ['GET'])]
    // J'affiche tous mes produits 
    public function index(ProductRepository $productRepository, 
                          Request $request,
                          PaginatorInterface $paginator): Response
    {
        // Je pagine les pages après avoir récupéré tous mes produits
        $products = $paginator->paginate(
            $productRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
          6 /*limit per page*/
        );

        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/new', name: 'admin_product_new', methods: ['GET', 'POST'])]
     // Méthode permettant la création d'un nouveau produit
    public function new(Request $request, 
                        EntityManagerInterface $entityManager,
                        HandleImage $handleImage): Response
    {
        // j'instancie un nouveau objet product
        $product = new Product();
        // Création d'un formulaire à partir de ProductType
        // Je y injecte l'instance (l'objet) de la classe produit précédemment créée
        $form = $this->createForm(ProductType::class, $product);
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
                $handleImage->save($file,$product);
            }

            // indiquer à EntityManager que ce produit devra être enregistré
            $entityManager->persist($product);
            // enregistrement de l'objet dans la BDD
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","Le produit a bien été ajouté.");
            // redirection de la page vers la page ci-dessous
            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }
        //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->renderForm('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_product_edit', methods: ['GET', 'POST'])]
    // Méthode permettant la modification du produit
    public function edit(Request $request, 
                         Product $product, EntityManagerInterface $entityManager,
                         HandleImage $handleImage): Response
    {
        // Récupération de l'image du produit
        $oldImage = $product->getImage();

        // Création d'un formulaireà partir de ProductType
        // Je y injecte l'instance (l'objet) de la classe Product précédemment créée
        $form = $this->createForm(ProductType::class, $product);
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
                $handleImage->edit($file,$product,$oldImage);
            }
            
            // L'entity manager enregistre les modifications en bdd
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","Le produit a bien été modifié.");

            //Je redirige vers la route de mon choix.
            return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
        }

        //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->renderForm('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_product_delete', methods: ['POST'])]
    // Méthode permettant la suppression d'un produit
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        // Si le Csrf token est valide
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {

            // Remove permet d'indiquer la suppression du produit
            $entityManager->remove($product);
            // flush supprime le produit
            $entityManager->flush();

            //J'envoie un message flash
            $this->addFlash("success","Le produit a bien été supprimé.");
        }
        //Je redirige vers la route de mon choix.
        return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
