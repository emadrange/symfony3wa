<?php

namespace Troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Troiswa\BackBundle\Form\ContactType;

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
    
    /**
     * Page contact
     * @author Eric
     * 
     * @param Request $request
     * @return type
     */
    public function contactAction(Request $request)
    {
        $formContact = $this->createForm(new ContactType(), null, [
            'attr' => [
                'novalidate' => 'novalidate'
            ]
        ])
                ->add('submit', 'submit', [
                    'label' => 'Envoyer',
                    'attr' => [
                        'class' => 'btn btn-default'
                    ]
                ]);
        
        $formContact->handleRequest($request);
        
        if ($formContact->isValid())
        {
            $datas = $formContact->getData();
            
            $message = \Swift_Message::newInstance()
                ->setSubject($datas["subject"])
                ->setFrom('send@example.com')
                ->setTo($datas["email"])
                ->setBody($this->renderView('TroiswaBackBundle:Mails:contact-email.html.twig', [
                    "data" => $datas
                ]), 'text/html')
                ->addPart($this->renderView('TroiswaBackBundle:Mails:contact-email.txt.twig', [
                    "data" => $datas
                ]), 'text/plain'
                );

            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add("success", "Votre mail a bien été envoyé");
            
            return $this->redirectToRoute('troiswa_front_homepage');
        }
        
        return $this->render('TroiswaFrontBundle:Main:contact.html.twig', [
            'form_contact' => $formContact->createView()
        ]);
    }

    /**
     * Retourne le nombre d'article du caddie
     * @author Eric
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cartCountAction()
    {
        $cart = $this->get('troiswa_front.cart');

        $nbArticle = count($cart->getCart());
        return $this->render('TroiswaFrontBundle:Main:cart-count.html.twig', [
            'nbArticle' => $nbArticle
        ]);
    }
}
