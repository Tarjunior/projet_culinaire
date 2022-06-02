<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            'required' => false,
            'label' => 'Nom de la recette',
            'attr' => [
                'placeholder' => 'Taper le nom ici...'
            ]
        ])

        ->add('file',FileType::class,[
            'mapped' => false,
            'label' => 'Upload une image',
            'required' => false,
            'constraints' => [
                // new NotBlank([
                //     'message' => 'Vous devez ajouter une image'
                // ]),
                new File([
                    'maxSize' => '1m',
                    'maxSizeMessage' => 'Le poids ne peut dépasser 1mo. Votre fichier est trop lourd.'
                ])
            ]
        ])

        ->add('presentation',CKEditorType::class,[
            'required' => false,
            'label' => 'Présentation',
            // 'constraints' => [
            //     new NotBlank([
            //         'message' => 'Présenter la recette ici...'
            //     ])
            // ]   
        ])
        ->add('ingredient',CKEditorType::class,[
            'required' => false,
            'label' => 'Les ingrédients',
            'constraints' => [
                new NotBlank([
                    'message' => 'Taper les ingredients ici...'
                ])
            ]   
        ])
        
        ->add('preparation',CKEditorType::class,[
            'required' => false,
            'label' => 'La préparation',
            'constraints' => [
                new NotBlank([
                    'message' => 'Taper la préparation ici...'
                ])
            ]
        ])

        ->add('type',TextType::class,[
            'required' => false,
            'label' => 'Type (Salée / Sucrée)',
            'attr' => [
                'placeholder' => 'Taper le type ici...'
            ]
        ])  

        ->add('category',EntityType::class,[
            'required' => false,
            'label' => 'Catégorie de la recette : ',
            'class' => Category::class,
            'group_by' => 'type',
            'placeholder' => '-- Choisir --',
            'choice_label' => function ($category) {
                return $category->getName();
            },
        ])

        ->add('difficulty',TextType::class,[
            'required' => false,
            'label'=> 'Difficulté',
            'attr' => [
                'placeholder' => 'Niveau de difficulté'
            ]
        ]) 

        ->add('duration',TextType::class,[
            'required' => false,
            'label'=> 'Duré',
            'attr' => [
                'placeholder' => 'Duré de préparation...'
            ]
        ]) 

        ->add('portion',TextType::class,[
            'required' => false,
            'label'=> 'Portion',
            'attr' => [
                'placeholder' => 'Nombre de portion...'
            ]
        ]) 

        ->add('vegetarian', CheckboxType::class,[
            'required' => false,
            'label'=> 'Végétarien'
        ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
