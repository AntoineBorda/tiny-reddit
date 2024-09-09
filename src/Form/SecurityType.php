<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SecurityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                    'placeholder' => 'Ancien mot de passe',
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Votre nouveau mot de passe',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Confirmez votre nouveau mot de passe',
                    ],
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier le mot de passe',
                'attr' => [
                    'class' => 'btn w-100 btn-primary mt-4',
                ],
            ]);
    }
}
