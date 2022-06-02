<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('first_name',TextType::class,[
            'required' => false,
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Taper le prénom ici...'
            ]
        ])
        ->add('last_name',TextType::class,[
            'required' => false,
            'label' => 'Nom ',
            'attr' => [
                'placeholder' => 'Taper le nom ici...'
            ]
        ])
        ->add('email',EmailType::class,[
            'required' => false,
            'label' => 'E-mail',
            'attr' => [
                'placeholder' => 'example@example.com'
            ]
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => 'J\'accepte les conditions générales d\'utilisation et de vente et la politique de confidentialité  de Joie des Papilles.',
            'required' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les CGU et CGV et la politique de confidentialité.',
                ]),
            ],
        ])
        
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent correspondre.',
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'required' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'first_options'  => [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe est requis',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ],
            'second_options' => [
                'label' => 'Confirmer votre mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La confirmation du mot de passe est requise',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit faire au moins {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ],
           
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
