<?php

namespace App\Form;

use App\Entity\Category;
use App\Search\SearchItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('filterByName',TextType::class,[
            'label' => 'Filtrer par nom',
            'required' => false,
        ])
        // ->add('filterByType',TextType::class,[
        //     'label' => 'Filtrer par type(salée/sucrée)',
        //     'required' => false,
        // ])
        ->add('filterByCategory', EntityType::class,[
            'label' => 'Filtrer par catégorie : ',
            'placeholder' => '-- Choisir --',
            'class' => Category::class,
            'required' => false,
            'choice_label' => 'name'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => SearchItem::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix():string
    {
        return '';
    }
}
