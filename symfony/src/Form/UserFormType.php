<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Почта',
                'attr' => ['placeholder' => 'Укажите почту'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста, укажите почту',
                    ]),
                ],
            ])
//            ->add('roles')
            ->add('firstName', TextType::class, [
                'label' => 'Имя',
                'attr' => ['placeholder' => 'Укажите имя пользователя'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста, укажите имя',
                    ]),
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
