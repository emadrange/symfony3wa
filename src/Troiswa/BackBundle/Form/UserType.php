<?php

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', [
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
            ->add('username', 'text', [
                'label' => 'Identifiant'
            ])
            ->add('address', 'textarea', [
                'label' => 'Adresse'
            ])
            ->add('password', 'repeated', [
                'type' => 'password',
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répéter le mot de passe')

            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_user';
    }
}
