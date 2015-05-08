<?php

namespace Polcode\CasperBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class EventFormType extends AbstractType {
    
    public function getName() {
        return 'casper_event';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('eventName', 'text', array(
                    'label' => 'Event name',
                    'attr'=> array('class'=>'input-sm')
                ))
                ->add('location', 'text', array(
                    'label' => 'Location',
                    'attr'=> array('class'=>'input-sm')
                ))
                ->add('description', 'textarea', array(
                    'label' => 'Description',
                    'attr'=> array('class'=>'input-sm')
                ))
                ->add('eventStart','collot_datetime', array(
                    'label' => 'Event start',
                    'attr'=> array('class'=>'input-sm'),
                    'pickerOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'weekStart' => 1,
                        'autoclose' => true,
                        'keyboardNavigation' => true,
                        'language' => 'en',
                        'minuteStep' => 5,
                        'pickerReferer ' => 'default', //deprecated
                        'pickerPosition' => 'bottom-right',
                    ]
                ))
                ->add('eventStop','collot_datetime', array(
                    'label' => 'Event end',
                    'attr'=> array('class'=>'input-sm'),
                    'pickerOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'weekStart' => 1,
                        'autoclose' => true,
                        'keyboardNavigation' => true,
                        'language' => 'en',
                        'minuteStep' => 5,
                        'pickerReferer ' => 'default', //deprecated
                        'pickerPosition' => 'bottom-right',
                    ]
                ))
                ->add('eventSignUpEndDate','collot_datetime', array(
                    'label' => 'Sign up until',
                    'attr'=> array('class'=>'input-sm'),
                    'pickerOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'weekStart' => 1,
                        'autoclose' => true,
                        'keyboardNavigation' => true,
                        'language' => 'en',
                        'minuteStep' => 5,
                        'pickerReferer ' => 'default', //deprecated
                        'pickerPosition' => 'bottom-right',
                    ]
                ))
                ->add('maxGuests','integer', array(
                    'label' => 'Max guests',
                    'attr'=> array('class'=>'input-sm')
                ))
                ->add('latitude','text', array(
                    'label' => 'Latitude',
                    'attr'=> array(
                            'class'=>'input-sm',
                        )
                ))
                ->add('longitude','text', array(
                    'label' => 'Longitude',
                    'attr'=> array(
                            'class'=>'input-sm',
                        )
                ))
                ->add('private','checkbox', array(
                        'label'    => 'Private',
                        'required' => false,
                ));
    }
    
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Polcode\CasperBundle\Entity\Event',
            'validation_groups' => array('registration')
        ));
    }

}
