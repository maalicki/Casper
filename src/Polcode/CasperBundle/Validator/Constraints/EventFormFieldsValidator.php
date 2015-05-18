<?php

namespace Polcode\CasperBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;


class EventFormFieldsValidator extends ConstraintValidator {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function validate($object, Constraint $constraint) {
        
        if( $object->isEventDatesValid() ) {
            if( ! $object->isEventSignUpValid() ) {
                $this->context->addViolationAt('eventSignUpEndDate', 'Date ends after event end!');
                $this->context->addViolation('Requested sign up date cannot be later than requested end date');
            }
        } else {
            $this->context->addViolationAt('eventStop', 'Event ends before it starts!');
            $this->context->addViolation('Requested end date must be later than requested start date');
        }
        
    }

}
