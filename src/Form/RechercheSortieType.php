<?php

namespace App\Form;

use App\Entity\Campus;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST') //inutile pour du POST mais nécessaire pour du GET
            ->add('campus', EntityType::class, [
                'class'=> Campus::class
                //'choice_value' => function (Campus $entity) {
                //    return $entity->getId();
              //  },
        ])
            ->add('nom', TextType::class, [
                'required' =>false,
                'attr' => array(
                    'placeholder' => 'Le nom de la sortie contient...') ])

            ->add('debut', DateType::class, [
                'required' =>false,
                'label' => 'Entre',
                'widget'=>'single_text',
                'attr'=>array(
                    'min' => (new \DateTime())->format('d/m/Y'),
                    'max' => (new \DateTime())->format('d/m/Y')
                )

            ])
            ->add('fin', DateType::class, [
                'required' =>false,
                'label' => 'et',
                'widget'=>'single_text',
                'attr'=>array(
                    'min' => (new \DateTime())->format('d/m/Y'),
                    'max' => (new \DateTime())->format('d/m/Y')
                )
            ])
            ->add('organisateur', CheckboxType::class, [
                'required' =>false,
                'label' => 'Sortie dont je suis l\'organisateur(trice)'
            ])
            ->add('inscrit', CheckboxType::class, [
                'required' =>false,
                'label' => 'Sorties auxquelles je suis inscrit(e)'
            ])
            ->add('pas_inscrit', CheckboxType::class, [
                'required' =>false,
                'label' => 'Sorties auxquelles je ne suis pas inscrit(e)'
            ])
            ->add('passees', CheckboxType::class, [
                'required' =>false,
                'label' => 'Sorties qui sont passées'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
