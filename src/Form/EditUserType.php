<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\Extension\Core\Type\HiddenType;
// use Symfony\Component\Form\FormEvent;
// use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EditUserType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->token = $tokenStorageInterface;
    }

    /* 
        TO DO - make user available to change it's own password.
        It may be a button that leads to a new form.
    */
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
            ->add('isVerified', CheckboxType::class)
            // ->add('password', HiddenType::class)
            ->add('userProfile', ProfileType::class);

            if ($this->token->getToken()->getUser()->getRoles()[0]  != 'ROLE_ADMIN') {
                $builder->remove('roles');
                $builder->remove('isVerified');
            }
        

        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent): void {
            // $form = $formEvent->getForm();
            // if($this->token->getToken()->getUser()->getRoles()[0]  == 'ROLE_ADMIN') {
            //     $form->add('isVerified', CheckboxType::class);
        //     }
        // });

        /*
        Data Trransformer info:
        https://symfony.com/doc/current/form/data_transformers.html
        */
        if ($this->token->getToken()->getUser()->getRoles()[0]  == 'ROLE_ADMIN') {
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
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
