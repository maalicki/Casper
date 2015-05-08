<?php

namespace Polcode\CasperBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class EventFormType extends AbstractType {
    
    public function getName() {
        return 'casper_event';
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
                    'attr'=> array('class'=>'input-sm')
                    ), array( 'pickerOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii:00',
                        'weekStart' => 0,
                        'autoclose' => false,
                        'startView' => 'month',
                        'minView' => 'hour',
                        'maxView' => 'decade',
                        'todayBtn' => false,
                        'todayHighlight' => false,
                        'keyboardNavigation' => true,
                        'language' => 'en',
                        'forceParse' => true,
                        'minuteStep' => 5,
                        'pickerReferer ' => 'default', //deprecated
                        'pickerPosition' => 'bottom-right',
                        'viewSelect' => 'hour',
                        'showMeridian' => false,
                ]))
                ->add('eventStop','collot_datetime', array(
                    'label' => 'Event end',
                    'attr'=> array('class'=>'input-sm')
                    ))
                ->add('eventSignUp','collot_datetime', array(
                    'label' => 'Sign up until',
                    'attr'=> array('class'=>'input-sm')
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
