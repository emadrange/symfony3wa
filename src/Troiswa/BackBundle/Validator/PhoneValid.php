<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class PhoneValid
 * @package Troiswa\BackBundle\Validator
 * @Annotation
 */
class PhoneValid extends Constraint {

    public $message = "Le numéro de téléphone n'est pas valide";
}

