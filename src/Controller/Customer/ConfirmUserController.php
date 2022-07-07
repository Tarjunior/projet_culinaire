<?php

namespace App\Controller\Customer;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfirmUserController extends AbstractController
{
    #[Route('/confirmationuser/{token}', name: 'app_confirm_email')]
    // Méthode permettant la confirmation d'un utilisateur
    public function confirmUser(string $token, UserRepository $userRepository, 
                                EntityManagerInterface $em)
    {
        // Je vérifie s'il y a bien un token en BDD
        $emailCustomer = $userRepository->findOneBy([
            'tokenConfirmationEmail' => $token
        ]);

        // S'il existe je le valide et je l'enregistre
        if($emailCustomer)
        {
            $emailCustomer->setIsConfirmed(true);
            $em->flush();

            $this->addFlash('success','Votre compte est maintenant validé. Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('home');
        
    }
}
