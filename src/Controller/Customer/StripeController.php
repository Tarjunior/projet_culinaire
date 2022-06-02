<?php 

namespace App\Controller\Customer;

use Stripe\Stripe;
use App\Entity\User;
use Stripe\Checkout\Session;
use App\Services\CartService;
use App\Services\CartRealProduct;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/stripe/checkout', name: 'stripe_checkout')]
    public function createSession(CartService $cartService)
    {
        Stripe::setApiKey('sk_test_51KX5hbBEB9wFzFNGlyXAca80fTHglcwNubLv8YJN867t9DSSMVNbz6pIZ4K1AQFdcmoeAel89vbROKpLIWvwkWBq00EvUL4P8n');
        

        $domain = 'http://127.0.0.1:8000/';

        /** @var CartRealProduct[] $detailCart */
        $detailCart = $cartService->detail();

        $productForStripe = [];

        /** @var User $user */
        $user = $this->getUser();

        foreach($detailCart as $item)
        {
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getProduct()->getPrice(),
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                        'images' => [
                            $domain . $item->getProduct()->getImage()
                        ]
                    ]
                ],
                'quantity' => $item->getQty()
            ];
        }

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => [
                'card',
            ],
            'line_items' => [
                $productForStripe
            ],
            'mode' => 'payment',
              'success_url' => $domain . 'paiementreussi/'. $user->getId(),
              'cancel_url' => $domain . 'paiementechoue',
          ]);

          return $this->redirect($checkout_session->url);

    }
}