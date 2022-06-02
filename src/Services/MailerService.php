<?php 

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService 
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendContactMail($emailCustomer,$content)
    {
        $email = (new TemplatedEmail())
            ->from('contact@joie-de-papilles.com')
            ->to($emailCustomer)
            ->subject('Message de contact de client')
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'content' => $content,
                'emailCustomer' => $emailCustomer
            ])
        ;
        
        $this->mailer->send($email);

    } 

    public function sendConfirmationEmail($emailCustomer)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@joie-de-papilles.com')
            ->to($emailCustomer->getEmail())
            ->subject('Confirmation de votre compte par email')
            ->htmlTemplate('email/confirmation_account.html.twig')
            ->context([
                'emailCustomer' => $emailCustomer
            ]);

        $this->mailer->send($email);
    }

    public function sendLostPasswordEmail($emailCustomer)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@joie-de-papilles.com')
            ->to($emailCustomer->getEmail())
            ->subject('Modification de votre mot de passe')
            ->htmlTemplate('email/password_lost.html.twig')
            ->context([
                'user' => $emailCustomer
            ]);

        $this->mailer->send($email);
    }

    public function sendOrderMail($emailCustomer)
    {
        $email=(new TemplatedEmail())
        ->from('noreply@joie-de-papilles.com')
        ->to($emailCustomer->getEmail())
        ->subject('Details de la commande')
        ->htmlTemplate('email/order_details.html.twig')
        ->context([
            'user' => $emailCustomer
        ]);

    $this->mailer->send($email);
    }

}