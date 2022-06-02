<?php 

namespace App\Controller\Customer;

use App\Entity\User;
use App\Services\CartService;
use App\Services\MailerService;
use App\Repository\UserRepository;
use App\Security\CaptainAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandShopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SuccessCommandShopController extends AbstractController
{
    #[Route('paiementreussi/{id}', name: 'stripe_success_payment')]
    public function success($id, Request $request, 
                            UserAuthenticatorInterface $userAuthenticator,
                            CaptainAuthenticator $authenticator,
                            UserRepository $userRepository,
                            EntityManagerInterface $em, 
                            CommandShopRepository $commandShopRepository, 
                            CartService $cartService, MailerService $mailer)
    {
        /** @var User $user */
        $user = $userRepository->find($id);

        if($user)
        {

            $commandShop = $commandShopRepository->findOneBy([
                'user' => $user
            ],[
                'id' => 'DESC'
            ]);

            $commandShop->setIsPayed(true);

            $em->flush();

            $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            //Vide le panier après la commande passée
            $cartService->emptyCart();

            $mailer->sendOrderMail($user);

            return $this->redirectToRoute("thank_you_page");
        }

        return $this->redirectToRoute("home");


    }

    #[Route('paiementechoue', name: 'stripe_cancel_payment')]
    public function cancel()
    {
        $this->addFlash("info","Votre paiment a échoué");
        return $this->redirectToRoute("cart_detail");
    }
}