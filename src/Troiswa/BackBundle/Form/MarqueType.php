<?php

namespace Troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class MarqueType extends AbstractType
{
    /**
     * Formulaire de l'entité Marque
     * @author Eric
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('products', 'entity', [
                'class' => 'TroiswaBackBundle:Product',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('prod')
                            ->orderBy('prod.title', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'by_reference' => false,
                'label' => 'Produits'
            ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Marque'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_marque';
    }
}
