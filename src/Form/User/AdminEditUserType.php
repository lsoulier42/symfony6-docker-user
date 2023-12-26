<?php

namespace App\Form\User;

use App\Dto\User\AdminEditUserDto;
use App\Enum\UserRoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminEditUserType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminEditUserDto::class
        ]);
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
                    'label' => 'global.label.email'
                ]
            )
            ->add(
                'enabled',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'global.label.enabled',
                ]
            )
            ->add(
                'role',
                EnumType::class,
                [
                    'class' => UserRoleEnum::class,
                    'required' => true,
                    'label' => 'global.label.role'
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'global.label.confirm',
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ]
            );
    }
}
