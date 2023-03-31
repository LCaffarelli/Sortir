<?php
namespace App\Form;

use App\Class\FiltresSorties;
use App\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltresSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Sites', EntityType::class, [
                'label' => "Site : ",
                'required' => false,
                'class' => Site::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                }
                ,//Choix liste déroulantes
                'multiple' => false])
            ->add('textRecherche', TextType::class, [
                'label' => "Nom : ",
                'required' => false
            ])
            ->add('firstDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('secondeDate', DateType::class, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('organisateur', CheckboxType::class, [
                'label' => 'Mes sorties',
                'required' => false,
            ])
            ->add('inscrit', CheckboxType::class, [
                'label' => 'Mes inscriptions',
                'required' => false,
            ])
            ->add('nonInscrit', CheckboxType::class, [
                'label' => 'Sorties Auxquelles je ne suis pas inscrit(e)',
                'required' => false,
            ])
            ->add('oldSortie', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FiltresSorties::class,
        ]);
    }
}
