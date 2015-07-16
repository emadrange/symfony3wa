<?php

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Antigrosmots
 * @package Troiswa\BackBundle\Validator
 * @Annotation
 */
class Antigrosmots extends Constraint
{
    public $message = "Il y a %nombre% gros mot(s) dans le texte.";
}