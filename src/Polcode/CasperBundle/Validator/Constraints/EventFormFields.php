<?php

namespace Polcode\CasperBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EventFormFields extends Constraint {

    public function validatedBy() {
        return 'event_form_validator';
    }

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}
