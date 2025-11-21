<?php

namespace App\Form;

use App\Entity\Lieu;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CollecteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'ville',
                'required' => false,
                'placeholder' => 'Toutes les villes',
                //Car on ne veut pas hydrater ou modifier une entité Collecte — on veut juste récupérer des valeurs pour filtrer
                'mapped' => false,
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false,
                'label' => 'À partir du'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
