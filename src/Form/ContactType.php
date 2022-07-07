<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email_customer',EmailType::class,[
                'label' => 'Votre e-mail',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez ajouter un e-mail'
                    ])
                ]
            ])
            ->add('content',TextareaType::class,[
                'label' => 'Le contenu du message',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez ajouter un contenu'
                    ]),
                    new Regex(pattern: '/^[a-z à â ä é è ê ë ï î ô ö ù û ü ÿ ç œ 0-9,.!?:;\'-]+$/i', htmlPattern: '^[a-zA-Z]+$')

                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
