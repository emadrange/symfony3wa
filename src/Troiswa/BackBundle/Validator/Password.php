<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 20/07/15
 * Time: 14:57
 */

namespace Troiswa\BackBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Password
 * @package Troiswa\BackBundle\Validator
 * @Annotation
 */
class Password extends Constraint {

    public $message = "Le mot de passe n'est pas assez sécurisé";

    public $min = 8;

    public $minMessage = "Le mot de passe est trop court";

}