<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('pseudo', TextType::class, [
                'label' => 'Votre pseudo',
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
                    'label' => 'Votre email',
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Votre email',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre email',
                    'attr' => [
                        'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                        'placeholder' => 'Confirmez votre email',
                    ],
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier mon profil',
                'attr' => [
                    'class' => 'btn w-100 btn-primary mt-4',
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
