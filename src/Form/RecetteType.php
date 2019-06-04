<?php

namespace App\Form;

use App\Entity\Recette;
use App\Form\ConfigType;
use App\Form\IngredientType;
use App\Form\EtapeRecetteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecetteType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Quel est le nom de votre recette ?"))
            ->add('portion', IntegerType::class, $this->getConfiguration("Portion", "Pour combien de personnes est prévue cette recette ?"))
            ->add('ustensiles')
            ->add('ingredients', CollectionType::class,
            [
                'entry_type' => IngredientType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => "Liste des ingrédients :"
            ])
            ->add('etapesRecette', CollectionType::class,
            [
                'entry_type' => EtapeRecetteType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => "Etapes de réalisation de la recette :"
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
