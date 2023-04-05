<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CSVFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichierCSV', FileType::class, [
                'label'=> 'Enregistrement par fichier CSV : ',
                'constraints' => [
                     new File(['mimeTypes' => [
                         'text/csv',
                     ],
                         'mimeTypes' => 'text/csv',
                        'mimeTypesMessage' => 'Le fichier doit être au format CSV',
                         'maxSize' => '10K',
                         'maxSizeMessage' => 'Le fichier est trop gros ({{ size }} {{ suffix }}). La taille maximale autorisée est {{ limit }} {{ suffix }}.'
                     ])
                ]
            ])
            ->add('Enregistrer', SubmitType::class, [
                'label'=> 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
