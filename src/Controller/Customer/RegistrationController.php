<?php

namespace App\Controller\Customer;

use App\Entity\User;
use App\Services\MailerService;
use Doctrine\ORM\EntityManager;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, 
                            UserPasswordHasherInterface $userPasswordHasher, 
                            EntityManagerInterface $entityManager,
                            TokenGeneratorInterface $tokenGenerator,
                            MailerService $mailer): Response
    {
        //Je créé une instance de la classe User
        $user = new User();

        //Je créé mon formulaire avec comme modele  RegistrationFormType
        //J'envoie dans mon formulaire mon objet user, précédemment créé
        //Effectivement les champs rempli du formulaire vont hydrater l'objet User
        $form = $this->createForm(RegistrationFormType::class, $user);

        //La requete est analysé pour vérifier tout ce qui a été soumis dans le form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            // J'encode le password    
            $userPasswordHasher->hashPassword(
                    $user,
                    //Je recupere le password en clair qui vient du formulaire
                    $form->get('plainPassword')->getData()
                )
            );

            $token = $tokenGenerator->generateToken() . uniqid();

            $user->setTokenConfirmationEmail($token);

            //Je persiste (préparation avant l'envoi en BDD)
            $entityManager->persist($user);

            //J'envoie en bdd
            $entityManager->flush();
            // do anything else you need here, like send an email

            $mailer->sendConfirmationEmail($user);

            $this->addFlash('success', 'Un e-mail de confirmation de compte vous a été envoyé.');
            
            return $this->redirectToRoute('app_login');

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/register{id}', name: 'app_register_delete', methods:['POST'])]
    public function deleteUser(EntityManagerInterface $entityManager, 
                               User $user, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')))
        {
            //je récupère le user déjà connecté
            // $userNow = $userRepository->find($user);

            //j'instancie un nouvel objet Session
            $session = new Session();

            //Invalidate permet de fermer la session utilisateur
            $session->invalidate();

            // Remove permet d'indiquer la suppression de l'utilisateur
            $entityManager->remove($user);

            // flush supprime le user
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);

            
        }else{
            // si la suppression n'a pas pu s'éffectuer, un message d'erreur sera afficher sur la page home
            $this->addFlash('error', 'Votre lien n\'est pas valide !');
            return $this->redirectToRoute('home');
        }
    }
    
    
}
