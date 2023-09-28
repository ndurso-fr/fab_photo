<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Agree Terms',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Password',
                'required' => true,
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password', 'attr' => ['placeholder' => '*********']],
                'second_options' => ['label' => 'Repeat Password', 'attr' => ['placeholder' => '*********']],
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('firstname', TextType::class, [
                'required' => true,
                'label' => 'First Name',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('lastname', TextType::class, [
                'required' => true,
                'label' => 'Last Name',
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('pseudo', TextType::class, [
                'required' => true,
                'label' => 'Pseudo',
                'attr' => [
                    'class' => 'form-control'
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
