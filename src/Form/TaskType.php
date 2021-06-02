<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                [
                    'label'       => "Titre",
                    'attr'        => ['placeholder' => "le titre de la tache"],
                    'constraints' => new Length([
                        'min'        => '3',
                        'max'        => '255',
                        'minMessage' => "Le titre de la tache est invalide",
                    ]),
                ])
            ->add('content', TextType::class,
                [
                    'label'       => "Description",
                    'attr'        => ['placeholder' => "la description de la tache"],
                    'constraints' => new Length([
                        'min'        => '3',
                        'max'        => '255',
                        'minMessage' => "La description de la tache est invalide",
                    ]),
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
