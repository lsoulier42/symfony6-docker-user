<?php

namespace App\Form;

use App\Dto\LoginDto;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractUserType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => LoginDto::class,
                'csrf_protection' => true,
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id' => 'authenticate'
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                '_remember_me',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'global.label.remember_me'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'global.label.login',
                    'attr' => [
                        'class' => 'btn btn-success'
                    ]
                ]
            );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
