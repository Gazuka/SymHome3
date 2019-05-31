<?php

namespace App\Form;

use App\Entity\Recette;
use App\Form\IngredientType;
use App\Form\EtapeRecetteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('portion')
            ->add('ustensiles')
            ->add('ingredients', CollectionType::class,
            [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('etapesRecette', CollectionType::class,
            [
                'entry_type' => EtapeRecetteType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
