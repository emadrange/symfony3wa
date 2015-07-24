<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 15/07/15
 * Time: 13:07
 */

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Entity\Marque;
use Troiswa\BackBundle\Form\MarqueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class MarqueController extends Controller {

    /**
     * Ajoute une marque
     * @author Eric
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request) {

        $marque = new Marque();

        $formMarque = $this->createForm(new MarqueType(), $marque, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("submit", "submit", [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);

        $formMarque->handleRequest($request);

        if ($formMarque->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->getFilters()->disable('softdeleteable');

            $logo = $marque->getLogo();
            $logo->setAlt($marque->getTitle());
            
            //$logo->upload();

            $em->persist($marque);
            //$em->persist($logo);
            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "La marque a bien été ajoutée");

            return $this->redirectToRoute("troiswa_back_marque_add");
        }

        return $this->render("TroiswaBackBundle:Marque:add.html.twig", [
            "formMarque" => $formMarque->createView()
        ]);

    }

    /**
     * Liste les marques par ordre alphabétique
     * @author Eric
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();

        $marques = $em->getRepository("TroiswaBackBundle:Marque")
            ->findAllBrandOrderByTitle();

        return $this->render("TroiswaBackBundle:Marque:list.html.twig", [
            "marques" => $marques
        ]);
    }

    /**
     * Visualisation d'une marque
     * @author Eric
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("marque", options={
     *      "mapping": {"idmarque": "id"},
     *      "repository_method" = "findOneBrandWithLogoById"
     * })
     */
    public function showAction(Marque $marque) {

        return $this->render("TroiswaBackBundle:Marque:show.html.twig", [
            "marque" => $marque
        ]);
    }

    /**
     * Edite une marque
     * @author Eric
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @ParamConverter("marque", options={"mapping": {"idmarque": "id"}})
     */
    public function editAction(Marque $marque, Request $request) {

        $formMarque = $this->createForm(new MarqueType(), $marque, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("submit", "submit", [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);

        $em = $this->getDoctrine()->getManager();

        $formMarque->handleRequest($request);

        if ($formMarque->isValid()) {

            $logo = $marque->getLogo();
            if ($logo->getAlt() == null) {
                $logo->setAlt($marque->getTitle());
            }
            //$logo->upload();

            $em->flush();

            $this->get('session')->getFlashBag()->add("success", "La marque a bien été modifiée");
            return $this->redirectToRoute("troiswa_back_marque_edit", [
                "idmarque" => $marque->getId()
            ]);
        }

        return $this->render("TroiswaBackBundle:Marque:edit.html.twig", [
            "formMarque" => $formMarque->createView()
        ]);
    }

    /**
     * Supprime une marque
     * @author Eric
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @ParamConverter("marque", options={"mapping": {"idmarque": "id"}})
     */
    public function deleteAction(Marque $marque) {

        $em = $this->getDoctrine()->getManager();

        $em->remove($marque);
        $em->flush();

        return $this->redirectToRoute('troiswa_back_marque_list');
    }
}