<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pseudo', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                    'placeholder' => 'Votre pseudo',
                ],
            ])

            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'required' => true,
                'invalid_message' => 'Les emails ne correspondent pas.',
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Votre email',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Confirmez votre email',
                    ],
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Votre mot de passe',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Confirmez votre mot de passe',
                    ],
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'btn w-100 btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
