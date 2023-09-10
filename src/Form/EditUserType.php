<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'Client' => 'ROLE_CLIENT',
                    'Salesperson' => 'ROLE_SALESPERSON',
                    'Admin' => 'ROLE_ADMIN',
                ],
            ])
            ->add('password')
            ->add('isVerified')
            ->add('userProfile', ProfileType::class)
            ->add('save', SubmitType::class)
        ;

        /*
        Data Trransformer info:
        https://symfony.com/doc/current/form/data_transformers.html
        */
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray): string {
                    return implode(', ', $rolesAsArray);
                },
                function ($rolesAsString): array {
                    return explode(', ', $rolesAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
