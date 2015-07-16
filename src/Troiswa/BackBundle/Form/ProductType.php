<?php

namespace Troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\BackBundle\Entity\CategoryRepository;
use Troiswa\BackBundle\Entity\MarqueRepository;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', [
                'label' => 'Titre'
            ])
            ->add('description')
            ->add('price', 'money', [
                'label' => 'Prix'
            ])
            ->add('quantity', 'number', [
                'label' => 'Quantité'
            ])
            ->add('active', 'checkbox', [
                'label' => 'Activé'
            ])
            /* la requete doit être forcement executé avec un createQueryBuilder */
            ->add('cat', 'entity', [
                'label' => 'Catégorie',
                'required' => false,
                'class' => 'TroiswaBackBundle:Category',
                'query_builder' => function(CategoryRepository $cr) {
                    return $cr->getCategorysByPosition(true);
                }
            ])
            /* requete dans la déclaration du champ */
            /*->add('cat', 'entity', [
                'required' => false,
                'class' => 'TroiswaBackBundle:Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.position', 'ASC');
                }
            ])*/
            /* récupération direct de l'entité */
            /*->add('cat', 'entity', [
                'label' => 'Catégorie',
                'class' => 'TroiswaBackBundle:Category',
                'property' => 'titre'
            ])*/
            ->add('marque', 'entity', [
                'class' => 'TroiswaBackBundle:Marque',
                'query_builder' => function(MarqueRepository $mr) {
                    return $mr->findMarquesByOrderTitle(true);
                },
                'choice_label' => 'titleAndDate',
                'required' => true
            ])
            ->add('cover', new ProductCoverType(), [
                'label' => ' '
            ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\BackBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_product';
    }
}
