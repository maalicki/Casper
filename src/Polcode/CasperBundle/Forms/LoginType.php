<?php

namespace Polcode\CasperBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class LoginType extends AbstractType {
    
    public function getName() {
        return 'login_form';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', 'text', array(
                    'label' => 'Email or nick'
                ))
                ->add('password', 'password', array(
                    'label' => 'Pasword'
                ))
                ->add('save', 'submit', array(
                    'label' => 'Login',
                ));
    }
    
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Polcode\CasperBundle\Entity\User'
        ));
    }

}
