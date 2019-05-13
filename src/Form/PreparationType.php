<?php

namespace App\Form;

use App\Entity\Boite;
use App\Entity\Recette;
use App\Entity\Preparation;
use App\Repository\BoiteRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PreparationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('datePreparation')
            ->add('boites', EntityType::class, [
                'class' => Boite::class,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (BoiteRepository $repo){
                    return $repo->findByPreparationVide();
                }
            ])            
            ->add('recettes')
            ->add('repas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Preparation::class,
        ]);
    }
}
