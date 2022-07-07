<?php 

namespace App\Services;

use App\Services\CartItem;
use App\Services\CartRealProduct;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $productRepository;

    public function __construct(SessionInterface $session,ProductRepository $productRepository)
    {
        $this->session = $session;   
        $this->productRepository = $productRepository;
    }

    public function getCart()
    {
        // si le panier n'existe pas je l'initialise avec un tableau vide
        return $this->session->get('cart',[]);
    }

    // je sauvegarde le panier dans la session
    public function saveCart(array $cart)
    {
        return $this->session->set('cart',$cart);
    }

    // méthode pour add un produit
    public function add($id)
    {
        //Je vais chercher mon panier
        $cart = $this->getCart();

        foreach($cart as $item)
        {
            if($item->getId() === $id)
            {
                $qtyActuel = $item->getQty();

                $item->setQty($qtyActuel + 1);

                $this->saveCart($cart);
                return;
            }
        }

        //J'ajoute dans le panier un ITEM qui va representer chaque element du panier
        $cartItem = new CartItem();
        $cartItem->setId($id);
        $cartItem->setQty(1);

        $cart[] = $cartItem;

        $this->saveCart($cart);
        return;
    }

    public function detail()
    {
        //j'initialise un tableau vide
        $detailCart = [];

        //je vais chercher mon panier
        $cart = $this->getCart();

        //Je boucle sur mon panier
        foreach($cart as $item)
        {
            $product = $this->productRepository->find($item->getId());

            if(!$product)
            {
                continue;
            }

            $cartRealProduct = new CartRealProduct();
            $cartRealProduct->setProduct($product);
            $cartRealProduct->setQty($item->getQty());

            $detailCart[] = $cartRealProduct;
        }

        return $detailCart;
    }

    public function getTotal()
    {
        $total = 0;

        $cart = $this->getCart();

        foreach($cart as $item)
        {
            $product = $this->productRepository->find($item->getId());

            if(!$product)
            {
                continue;
            }

            $total += $product->getPrice() * $item->getQty();
        }

        return $total;
    }

    public function remove(int $id)
    {
        //Je vais chercher mon panier
        $cart = $this->getCart();

        //Je boucle sur mon panier
        foreach($cart as $key => $item)
        {
            if($item->getId() === $id)
            {
                unset($cart[$key]);
                $this->saveCart($cart);
            }
        }
    }

    public function decrement(int $id)
    {
        //Je vais chercher mon panier
        $cart = $this->getCart();

        //Je boucle dessus
        foreach($cart as $key => $item)
        {
            if($item->getId() === $id)
            {
                $qty = $item->getQty();

                if($qty === 1)
                {
                    unset($cart[$key]);
                    $this->saveCart($cart);
                    return;
                }
                else 
                {
                    $item->setQty($qty - 1);
                    $this->saveCart($cart);
                    return;
                }

            }
        }
    }
    
    //Vide le panier après la commande passée
    public function emptyCart()
    {
        $this->saveCart([]);
    }

}