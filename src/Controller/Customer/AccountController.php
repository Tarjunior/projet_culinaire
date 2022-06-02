<?php 

namespace App\Controller\Customer;

use App\Entity\User;
use App\Form\EditPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/account/modifiermotdepasse', name: 'account_edit_password')]
    // Méthode permettant la modification du mot de passe
    public function editPassword(Request $request, 
                                EntityManagerInterface $em,
                                UserPasswordHasherInterface $userPasswordHasher)
    {   
        // Je récupère l'user déjà connecté
        /** @var User $user */
        $user = $this->getUser();

        // Je créé un form
        $form = $this->createForm(EditPasswordType::class);

        // J'analyse la demande
        $form->handleRequest($request);

        // Si le mot de passe est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // L'entity manager enregistre en BDD le nouveau mdp
            $em->flush();
            // do anything else you need here, like send an email

            $this->addFlash("success","Votre mot de passe a bien été modifié.");

            return $this->redirectToRoute('account_edit_password');
        }

        return $this->render('customer/account/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}