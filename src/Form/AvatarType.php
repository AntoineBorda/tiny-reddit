<?php

namespace App\Form;

use App\Entity\Avatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('avatarFile', VichImageType::class, [
                'label' => 'Avatar',
                'download_uri' => false,
                'image_uri' => false,
                'allow_delete' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-sm bg-dark border-primary mb-2',
                ],
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Modifier l\'avatar',
                'attr' => [
                    'class' => 'btn w-100 btn-primary mt-4',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avatar::class,
            'translation_domain' => 'forms',
        ]);
    }
}
