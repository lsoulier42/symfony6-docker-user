<?php

namespace App\Form;

use App\Dto\ForgotPasswordRequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForgotPasswordRequestType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ForgotPasswordRequestDto::class
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                TextType::class,
                [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'global.label.email'
                    ]
                ]
            )
            ->add(
                'email',
                TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'global.label.email'
                    ]
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'global.label.send',
                    'attr' => [
                        'class' => 'form-control btn btn-primary rounded submit px-3'
                    ]
                ]
            );
    }
}
