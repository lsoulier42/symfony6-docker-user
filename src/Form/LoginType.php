<?php

namespace App\Form;

use App\Dto\LoginDto;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
                'data_class' => LoginDto::class
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
                'csrfToken',
                HiddenType::class
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
}
