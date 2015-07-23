<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Troiswa\BackBundle\Entity\User;
use Troiswa\BackBundle\Form\UserEditType;
use Troiswa\BackBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Security("has_role('ROLE_CLIENT')");
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TroiswaBackBundle:User')->findAll();

        return $this->render('TroiswaBackBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new User entity.
     *
     * @Security("has_role('ROLE_COMMERCIAL')");
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Hachage du mot de passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($entity);
            $newPassword = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($newPassword);

            // Affectation du role
            $role = $em->getRepository('TroiswaBackBundle:Role')->findOneByName('client');
            $entity->addRole($role);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('troiswa_back_user_show', array('id' => $entity->getId())));
        }

        return $this->render('TroiswaBackBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('troiswa_back_user_create'),
            'method' => 'POST',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ));

        $form->add('submit', 'submit', [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-default'
            ]
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Security("has_role('ROLE_COMMERCIAL')");
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('TroiswaBackBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a User entity.
     *
     * @Security("has_role('ROLE_CLIENT')");
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:User')->find($id);

        $coupons = $em->getRepository('TroiswaBackBundle:UserCoupon')
            ->getCouponsFromUser($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TroiswaBackBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'coupons'     => $coupons
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Security("has_role('ROLE_ADMIN')");
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TroiswaBackBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserEditType(), $entity, array(
            'action' => $this->generateUrl('troiswa_back_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ));

        $form->add('submit', 'submit', [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-default'
            ]
        ]);

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Security("has_role('ROLE_ADMIN')");
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TroiswaBackBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {


            $em->flush();

            return $this->redirect($this->generateUrl('troiswa_back_user_edit', array('id' => $id)));
        }

        return $this->render('TroiswaBackBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a User entity.
     *
     * @Security("has_role('ROLE_SUPER_ADMIN')");
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TroiswaBackBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('troiswa_back_user_list'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('troiswa_back_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Supprimer'
                ])
            ->getForm()
        ;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $newUser = new User();
        $formRegister = $this->createForm(new UserType(), $newUser, [
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ])
        ->add('submit', 'submit', [
            'label' => 'Créer le compte'
        ]);

        $formRegister->handleRequest($request);

        if ($formRegister->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('troiswa_back_login');
        }

        return $this->render("TroiswaBackBundle:User:register.html.twig", [
            'form_register' => $formRegister->createView()
        ]);
    }
}
