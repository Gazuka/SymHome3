<?php

namespace App\Form;

use App\Entity\EtapeRecette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtapeRecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descriptif')
            ->add('position')
            ->add('ingredients')
            ->add('ustensiles')
            //->add('recette')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EtapeRecette::class,
        ]);
    }
}
