<?php

namespace App\Controller\Customer;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcommerceProductController extends AbstractController
{
    #[Route('/ecommerce/product/{id}', name: 'ecommerce_product')]
    public function index(int $id, ProductRepository $productRepository): Response
    {
        //Je vais chercher mon produit grace au ProductRepository 
        //Et grace a l'identifiant $id que je recois en paramètre
        $product = $productRepository->find($id);

        //Si le produit n'existe pas on redirige vers la page d'accueil
        if(!$product)
        {
            return $this->redirectToRoute("ecommerce");
        }

        //si tout va bien , on affiche le rendu html du fichier twig et on envoie dans ce fichier twig
        //les variables nécessaires
        return $this->render('customer/ecommerce_product/index.html.twig', [
            'product' => $product,
        ]);
    }

    
}
