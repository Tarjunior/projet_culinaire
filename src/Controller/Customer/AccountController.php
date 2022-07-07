<?php 

namespace App\Controller\Customer;

use App\Entity\User;
use App\Form\EditPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    #[Route('/profile/account/modifiermotdepasse', name: 'account_edit_password')]
    // Méthode permettant la modification du mot de passe
    public function editPassword(Request $request, 
                                EntityManagerInterface $em,
                                UserPasswordHasherInterface $userPasswordHasher): Response
    {   
        // Je récupère l'user déjà connecté
        /** @var User $user */
        $user = $this->getUser();

        // Je créé un form à partir de EditPasswordType
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

            //Si toutes les étapes sont validées, j'envoie un message flash
            $this->addFlash("success","Votre mot de passe a bien été modifié.");
            
            // Je redirige vers cette route
            return $this->redirectToRoute('account_edit_password');
        }

        //Ici, si le formulaire n'a pas été soumis, on affiche la page
        return $this->render('customer/account/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}