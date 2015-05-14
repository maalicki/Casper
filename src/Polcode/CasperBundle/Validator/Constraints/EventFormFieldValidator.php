<?php

namespace Polcode\CasperBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

/**
 * @Annotation
 */
class EventFormFieldsValidator extends ConstraintValidator {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function validate($object, Constraint $constraint) {
        die('end'); /* just for check if  */
        $this->context->addViolationAt('eventStart', 'There is already an event during this time!');
        $this->context->addViolation('There is already an event during this time!');
    }

}
