<?php

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * Index du site
     * @author Eric
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        // images produit pour le slider
        $imageProducts = $em->getRepository('TroiswaBackBundle:Product')
                ->findRecentImagesProductByLimit(3);

        // Produits avec le plus de tag
        $productsByGreatestTag = $em->getRepository('TroiswaBackBundle:Product')
                ->findProductsByGreatestTag(6);

        return $this->render('TroiswaFrontBundle:Main:index.html.twig', [
            'imageProducts' => $imageProducts,
            "productsByGreatestTag" => $productsByGreatestTag
        ]);
    }
}
