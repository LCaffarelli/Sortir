<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])

            ->add('dateLimiteInscription', DateType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])

            ->add('nbInscriptionsMax')
            ->add('duree', TimeType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ])

            ->add('infosSortie')

            ->add('lieu', EntityType::class, ['class'=> Lieu::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                }
                ,//Choix liste déroulantes
                'multiple' => false])

            ->add('publier', SubmitType::class,[
                'label'=>"Publier la sortie",
            ])
            ->add('cree', SubmitType::class,[
                'label'=>"Enregistrer",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
