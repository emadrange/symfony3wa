<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 20/07/15
 * Time: 13:57
 */

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneValidValidator extends ConstraintValidator {

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!preg_match('/^((\+|00)33\s?|0)[1-9](\s?\d{2}){4}$/', $value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}