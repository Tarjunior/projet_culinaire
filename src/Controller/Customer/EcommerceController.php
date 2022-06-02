<?php

namespace App\Controller\Customer;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EcommerceController extends AbstractController
{
    #[Route('/ecommerce', name: 'ecommerce')]
    public function index(ProductRepository $productRepository, 
                        CategoryRepository $categoryRepository,  
                        PaginatorInterface $paginator, 
                        Request $request): Response
    {
        $products = $paginator->paginate(
            $productRepository->findBy([],['id' => 'DESC']), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        
        return $this->render('customer/ecommerce/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $products,
        ]);
    }
}
