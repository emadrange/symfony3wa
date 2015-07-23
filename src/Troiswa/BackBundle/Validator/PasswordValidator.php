<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 20/07/15
 * Time: 15:00
 */

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator {


    /**
     * Checks if the passed value is valid.
     * @author Eric
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (strlen($value) < $constraint->min) {
            $this->context->addViolation($constraint->minMessage);
        }
        /*
         * RegEx pour mot de passe infernal
         * /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@_-])[A-Za-z\d$@_-]{8,}$/
         */
        elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*)[A-Za-z\d]{8,}$/', $value)) {
            $this->context->addViolation($constraint->message);
        }


    }
}