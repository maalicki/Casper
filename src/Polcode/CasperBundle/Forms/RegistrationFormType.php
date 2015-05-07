<?php

namespace Polcode\CasperBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType {
    
    public function getName() {
        return 'casper_user_registration';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', 'email', array(
                    'label' => 'Email'
                ))
                ->add('sex', 'choice', array(
                    'label' => 'Sex',
                    'choices' => array(
                        'm' => 'Male',
                        'f' => 'Famale'
                    ),
                    'expanded' => true
                ))
                ->add('birthdate', 'birthday', array(
                    'label' => 'Birth Date',
                    'empty_value' => '--',
                    'empty_data' => NULL
                ))
                ->add('rules', 'checkbox', array(
                    'label' => 'Accpet rules',
                    'constraints' => array(
                        new Assert\NotBlank()
                    ),
                    'mapped' => false
                ));
    }
    
    public function getParent()
    {
        return 'fos_user_registration';
    }

    
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Polcode\CasperBundle\Entity\User',
            'validation_groups' => array('registration')
        ));
    }

}
