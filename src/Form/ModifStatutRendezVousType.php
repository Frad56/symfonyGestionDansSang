<?php

namespace App\Form;

use App\Entity\Collecte;
use App\Entity\Donateur;
use App\Entity\RendezVous;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifStatutRendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateHeureDebut')
            ->add('dateheureFin')
            ->add('statut')
            ->add('donateur', EntityType::class, [
                'class' => Donateur::class,
                'choice_label' => 'id',
            ])
            ->add('collecte', EntityType::class, [
                'class' => Collecte::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
