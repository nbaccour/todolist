<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class,
                [
                    'label' => 'Nouveau mot de passe',
                    'attr'  => ['placeholder' => 'votre mote de passe'],
                ])
            ->add('verifPassword', PasswordType::class,
                [
                    'label' => 'Confirmation du mot de passe',
                    'attr'  => ['placeholder' => 'Répétez votre mot de passe'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
