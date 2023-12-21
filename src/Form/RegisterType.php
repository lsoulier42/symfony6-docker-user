<?php

namespace App\Form;

use App\Dto\UserDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UserDto::class
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
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'global.form.register.error.password',
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
                    'label' => 'global.label.register',
                    'attr' => [
                        'class' => 'form-control btn btn-primary rounded submit px-3'
                    ]
                ]
            );
    }
}
