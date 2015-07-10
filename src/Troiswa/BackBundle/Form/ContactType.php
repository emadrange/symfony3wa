<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 07/07/15
 * Time: 09:48
 */

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("firstname", "text",[
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut un prénom"
                ]),
                new Assert\Length([
                    "min" => 2,
                    "minMessage" => "2 caractères minimum"
                ]),
                new Assert\Regex([
                    "pattern" => '/^[a-zA-Z]/',
                    "match" => true,
                    "message" => "Il y a des chiffres dans le prénom"
                ])
            ]
        ])
            ->add("lastname", "text",[
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => "il faut un nom"
                    ]),
                    new Assert\Length([
                        "min" => 5,
                        "minMessage" => "5 caractères minimum"
                    ])
                ]
            ])

            ->add("subject", "choice", [
                "choices" => [
                    "technique"=>"Téchnique",
                    "commercial"=>"Commercial",
                    "partenariat"=>"Partenariat"
                ],
                //"multiple" => false,
                "constraints" => [
                    new Assert\Choice([
                        "choices" => [
                            "technique", "commercial", "partenariat"
                        ],
                        "message" => "choix demandé impossible"
                    ])
                ]
            ])
            ->add("email", "email",[
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => "il faut une adresse mail"
                    ]),
                    new Assert\Email([
                        "checkMX" => true,
                        "message" => "l'adresse n'est pas correcte"
                    ])
                ]
            ])
            ->add("contenu", "textarea",[
                "constraints" => [
                    new Assert\NotBlank([
                        "message" => "il faut un message"
                    ]),
                    new Assert\Length([
                        "max" => 500,
                        "maxMessage" => "Votre message est trop long"
                    ])
                ]
            ])
            ->add("submit", "submit");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getName()
    {
        return 'form_contact';
    }
}