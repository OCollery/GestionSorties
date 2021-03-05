<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null, [
                "label" => 'Nom de la sortie: '
            ])
            ->add('dateHeureDebut',null,[
                "label" => "Date et heure de la sortie: "
            ])
            ->add('dateLimiteInscription',null, [
                "label" => "Date limite d'inscription: "
            ])
            ->add('nbInscriptionsMax', null, [
                "label" => "Nombre de places: "
            ])
            ->add('duree',null,[
                "label" => "Durée: "
            ])
            ->add('descriptioninfos', TextareaType::class, [
                "label" => "Descritpion et infos: "
            ])
            ->add('campus', null, [
                "label" => "Campus: "
            ])
            ->add('lieu', null, [
                "label" => "Lieu: "
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
