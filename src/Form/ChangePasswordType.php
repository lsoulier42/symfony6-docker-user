<?php

namespace App\Form;

use App\Dto\ChangePasswordDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ChangePasswordDto::class
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'global.form.change_password.error.password',
                    'required' => true,
                    'first_options' =>
                        [
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'global.label.password'
                            ]
                        ],
                    'second_options' =>
                        [
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'global.label.confirm_password'
                            ]
                        ],
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
