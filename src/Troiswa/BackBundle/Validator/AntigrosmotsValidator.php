<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 16/07/15
 * Time: 11:01
 */

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntigrosmotsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        //die();
        $badWords = [
            'merde',
            'chier',
            'con',
            'connard'
        ];

        foreach($badWords as $badWord) {
            if (preg_match('/\b' . $badWord . '\b/i', $value)) {
                $this->context->addViolation($constraint->message);
                break;
            }
        }

        // Créer un tableau de gros mots
        // Si la valeur tapé par l'utilisateur est dans le tableau
        // Création d'une erreur

    }
}