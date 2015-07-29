<?php

namespace Troiswa\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Troiswa\FrontBundle\Repository\CommentRepository;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $comment = $builder->getData();
        $idProduct = $comment->getProduct()->getId();

        $builder
            ->add('content', 'textarea', [
                'label' => 'Commentaire'
            ])
            ->add('rate', 'integer', [
                'label' => 'Note'
            ])
            ->add('parent', 'entity', [
                'class' => 'TroiswaFrontBundle:Comment',
                'query_builder' => function(CommentRepository $cr) use ($idProduct) {

                    return $cr->findCommentsByProductId($idProduct, true);
                },
                'choice_label' => 'id'
            ])
            //->add('created')
            //->add('product')
            //->add('client')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troiswa\FrontBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_frontbundle_comment';
    }
}
