<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                [
                    'label'       => "Nom de l'utilisateur",
                    'attr'        => ['placeholder' => "le nom de l'utilisateur"],
                    'constraints' => new Length([
                        'min'        => '3',
                        'max'        => '255',
                        'minMessage' => "Le nom de l'utilisateur est Invalide",
                    ]),
                ])
            ->add('email', EmailType::class,
                ['label' => 'Adresse email', 'attr' => ['placeholder' => 'Adresse email']])
            ->add('password', PasswordType::class,
                [
                    'label' => 'mot de passe',
                    'attr'  => ['placeholder' => 'mote de passe'],
                ])
            ->add('verifPassword', PasswordType::class,
                [
                    'label' => 'Confirmation du mot de passe',
                    'attr'  => ['placeholder' => 'Répétez le mot de passe'],
                ])
            ->add('roles', ChoiceType::class, [
                'label'       => 'Role',
                'placeholder' => '-- choisir un role --',
                'choices'     => [
                    'administrateur' => 'ROLE_ADMIN',
                    'utilisateur'    => 'ROLE_USER',
                ],
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
