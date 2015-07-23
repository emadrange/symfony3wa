<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 23/07/15
 * Time: 12:07
 */

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('firstname', 'text', [
        'label' => 'Prénom'
        ])
        ->add('lastname', 'text', [
            'label' => 'Nom'
        ])
        ->add('email', 'email', [
            'label' => 'Mail'
        ])
        ->add('birthday', 'date', [
            'label' => 'Date de naissance',
            "data" => new \DateTime("now"),
            "years" => range(date('Y')-90,date('Y')),
            "format" => 'dd-MM-yyyy'

        ])
        ->add('phone', 'text', [
            'label' => 'Téléphone'
        ])
        ->add('pseudo', 'text', [
            'label' => 'Identifiant'
        ])
        ->add('address', 'textarea', [
            'label' => 'Adresse'
        ])
        ->add('role', 'entity', [
            'class' => 'TroiswaBackBundle:Role',
            'multiple' => true,
            //'expanded' => true,
            'choice_label' => 'name'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'troiswa_backbundle_user_edit';
    }
}