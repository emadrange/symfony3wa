<?php

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // images produit pour le slider
        $imageProducts = $em->getRepository('TroiswaBackBundle:Product')
                ->findRecentImagesProductByLimit(3);

        return $this->render('TroiswaFrontBundle:Main:index.html.twig', [
            'imageProducts' => $imageProducts
        ]);
    }

}
