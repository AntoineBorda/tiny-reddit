<?php

namespace App\Form;

use App\Entity\Expression;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('expression', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Si elle n\'existe pas, rédige ta proposition ici',
                    'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                    'rows' => 5,
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-primary w-100 btn-xl',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expression::class,
        ]);
    }
}
