<?php

namespace App\Form;

use App\Entity\Donateur;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Lieu;
class DonateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class,[
                'label' => 'Name',
                'required' => true
            ])
            ->add('email', TextType::class,[
                'label' => 'Email',
                'required' => true
            ])

            ->add('password', TextType::class,[
                'label' => 'Password',
                'required' => true
            ])
           
            ->add('groupeSanguin', ChoiceType::class,[
                'label' => 'Blood Type',
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'O' => 'O',
                    'AB+' => 'AB+'
                ],
                'placeholder' => 'Blood Type',
                'required' => true
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nomLieu',
                'placeholder' => 'Choisissez votre lieu',
                //Le champ sera dans le formulaire
                //Symfony n’essaiera pas de remplir l’entité Donateur
                //sinon cette Erreur Can't get a way to read the property 'lieu' in class Donateur doit etre afficher
                'mapped' => false,
            ])
            
            ->add('derniereDateDon', DateType::class,[
                'label' => 'derniereDateDon',
                'required' => false
            ])
           
           
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donateur::class,
        ]);
    }
}
