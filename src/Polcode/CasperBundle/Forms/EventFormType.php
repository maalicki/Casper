<?php

namespace Polcode\CasperBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType {
    
    public function getName() {
        return 'casper_user_registration';
    }
/*
 * Jako użytkownik mogę stworzyć nowy event podając 
 *  nazwę, 
 *  miejsce, 
 *  opis, 
 *  datę i godzinę, 
 *  czas trwania, 
 *  maksymalną liczbę gości, 
 *  datę końca zbierania zgłoszeń, 
 *  mogę zaznaczyć na mapie Google'a miejsce spotkania,
 *  zaznaczyć czy event jest publiczny czy prywatny
 * 
 * - event name
 * - location
 * - description
 * - evet_start [ datetime ]
 * - signup_enddate [date]
 * - duration
 * - max_guests,
 * - latitude/longitude
 * - isPrivate
 * 
 */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('eventname', 'text', array(
                    'label' => 'Event name'
                ))
                ->add('location', 'text', array(
                    'label' => 'Location'
                ))
                ->add('description', 'text', array(
                    'label' => 'Description'
                ))
                ->add('eventstart','datetime', array(
                    'label' => 'Event start'
                ))
                ->add('signup_enddate','datetime', array(
                    'label' => 'Signup untill'
                ))
                ->add('duration','datetime', array(
                    'label' => 'Duration of the event'
                ))
                ->add('maxguests','integer', array(
                    'label' => 'Max guests'
                ))
                ->add('','', array(
                    
                ))
                ->add('isprivate','checkbox', array(
                        'label'    => 'Private',
                        'required' => false,
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
