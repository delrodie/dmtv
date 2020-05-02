<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Nom utilisateur", 'autocomplete'=>"off"]])
            ->add('email', EmailType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Adresse email"]])
            ->add('password', PasswordType::class,['attr'=>['class'=>'form-control', 'placeholder'=>"Mot de passe"]])
            ->add('roles', ChoiceType::class,[
                'choices'=>[
                    'Administrateur'=>'ROLE_ADMIN',
                    'Utilisateur'=>'ROLE_USER',
                ],
                'multiple'=>true,
                'expanded'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
