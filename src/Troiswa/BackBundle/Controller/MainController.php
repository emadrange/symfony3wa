<?php

namespace Troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Troiswa\BackBundle\Form\ContactType;


class MainController extends Controller
{
    /**
     * Index
     * @author Eric
     * @return Response
     */
    public function indexAction()
    {
        //die("Ok!!!");
        //return new Response("Hello!!!");

        //dump($request->query->get("page", 1)); // récupération de paramêtre $_GET
        //die();

        //$page = $request->query->get("page", 1);
        //$name = $request->query->get("name", "inconnu");

        //$idproduct = 31;
        $em = $this->getDoctrine()->getManager();

        // DSL
        /*$query = $em->createQuery("
            SELECT p
            FROM TroiswaBackBundle:Product p
            WHERE p.id = :idprod
        ")
        ->setParameter("idprod", $idproduct);*/

        // p => tableau d'object
        // p.title => tableau de tableau

        /*$query = $em->createQuery("
            SELECT prod
            FROM TroiswaBackBundle:Product prod
            WHERE prod.quantity > 5
        ");*/

        // Builder
        /*$query = $em->createQueryBuilder()
            ->select("prod")
            ->from("TroiswaBackBundle:Product", "prod")
            ->getQuery();*/

        //$products = $query->getResult();

        // Repository

        //$products = $em->getRepository("TroiswaBackBundle:Product")->findAllMaison();

        //$pdroductsByQuantity = $em->getRepository("TroiswaBackBundle:Product")->findProductsByQuantity();

        // Produits < 5
        $productsByMinimumQuantity = $em->getRepository("TroiswaBackBundle:Product")->findProductsByMinimumQuantity(5);

        // nombre de produit = 0
        $nbProductIsZero = $em->getRepository("TroiswaBackBundle:Product")->countProductByQuantityIsZero();

        // nombre de produit actif
        $nbActiveProduct = $em->getRepository("TroiswaBackBundle:Product")->countActiveProduct();

        // nombre de catégorie
        $nbCategory = $em->getRepository("TroiswaBackBundle:Category")->countCategory();

        // catégories dont la position > 2
        $categorysByPosition = $em->getRepository("TroiswaBackBundle:Category")->findCategorysByPosition(2);

        // nombre d'actif et non actif
        $statesActiveProduct = $em->getRepository("TroiswaBackBundle:Product")->countStatesActiveProduct();

        // Retourne les produits dont le prix est compris entre 2 valeurs
        $pricesBeetweenPrice = $em->getRepository("TroiswaBackBundle:Product")->findPricesByBeetweenPrice(250, 400);

        // Retourne les titres commençant par "le"
        $titleCategorysByBeginText = $em->getRepository("TroiswaBackBundle:Category")->findTitleCategorysByBeginText('le');

        // Retourne les catégories dont les produits ont une marque donnée
        $categorysFromProductByBrand = $em->getRepository("TroiswaBackBundle:Product")->getCategoryFromProductByBrand('Bachmann');


        //dump($categorysFromProductByBrand);
        //die();


        return $this->render("TroiswaBackBundle:Main:index.html.twig", [
            //"products" => $products,
            "productsByMinimumQuantity" =>$productsByMinimumQuantity,
            "nbProductIsZero" => $nbProductIsZero,
            "nbActiveProduct" => $nbActiveProduct,
            "nbCategory" => $nbCategory,
            "categorysByPosition" => $categorysByPosition,
            "statesActiveProduct" => $statesActiveProduct,
            "pricesBeetweenPrice" => $pricesBeetweenPrice,
            "titleCategorysByBeginText" => $titleCategorysByBeginText,
            "categorysFromProductByBrand" => $categorysFromProductByBrand
        ]/*, [
            "name" => $name,
            "page" => $page
        ]*/);
    }

    /**
     * Contact avec utilisation du formulaire ContactType
     * @author Eric
     * @param Request $request
     * @return Response
     */
    public function contactAction(Request $request) {

        //$formContact = $this->createFormBuilder(null, [
        //    "attr" => [
        //        "novalidate" => "novalidate"
        //    ]
        //])

        //    ->getForm();

        $formContact = $this->createForm(new ContactType(), null, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
            ->add("submit", "submit", [
                "label" => "Envoyer",
                "attr" => [
                    "class" => "btn btn-default"
                ]
            ]);

        //if ($request->isMethod("POST")) {
        //    $formContact->submit($request);

        $formContact->handleRequest($request);
            if ($formContact->isValid()) {
                $data = $formContact->getData();

                //dump($data);
                //die();

                $message = \Swift_Message::newInstance()
                    ->setSubject($data["subject"])
                    ->setFrom('send@example.com')
                    ->setTo($data["email"])
                    //->setBody("du contenu")
                    ->setBody($this->renderView('TroiswaBackBundle:Mails:contact-email.html.twig', [
                        "data" => $data
                    ]), 'text/html')
                    ->addPart(
                        $this->renderView('TroiswaBackBundle:Mails:contact-email.txt.twig', [
                            "data" => $data
                        ]), 'text/plain'
                    );

                //dump($message);
                //die();

                $this->get('mailer')->send($message);

                $this->get('session')->getFlashBag()->add("success", "Votre mail a bien été envoyé");

                //return $this->redirect($this->generateUrl("troiswa_back_contact"));

                return $this->redirectToRoute("troiswa_back_contact");
            }
        //}



        return $this->render("TroiswaBackBundle:Main:contact.html.twig", [
            "formContact" => $formContact->createView()

        ]);
    }

    /**
     * Feedback
     * @author Eric
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function feedbackAction(Request $request) {

        $formFeedback = $this->createFormBuilder(null, [
            "attr" => [
                "novalidate" => "novalidate"
            ]
        ])
        ->add("firstname", "text", [
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut un prénom"
                ]),
                new Assert\Length([
                    "min" => 2,
                    "minMessage" => "2 caractères minimum"
                ])
            ]
        ])
        ->add("lastname", "text", [
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut un nom"
                ]),
                new Assert\Length([
                    "min" => 2,
                    "minMessage" => "2 caractères minimum"
                ])
            ]
        ])
        ->add("mail", "email", [
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
        ->add("url", "url", [
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut une adresse"
                ]),
                new Assert\Url([
                    "message" => "L'adresse n'est pas valide"
                ])
            ]
        ])
        ->add("statut", "choice", [
            "choices" => [
                "affichage" => "Bug affichage",
                "fonctionnel" => "Bug fonctionnel",
                "hs" => "Rien ne marche"
            ],
            "expanded" => true,
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut un statut"
                ]),
                new Assert\Choice([
                    "choices" => [
                        "affichage", "fonctionnel", "hs"
                    ],
                    "message" => "choix demandé impossible"
                ])
            ]
        ])
        ->add("description", "textarea", [
            "constraints" => [
                new Assert\NotBlank([
                    "message" => "il faut un message"
                ]),
                new Assert\Length([
                    "min" => 10,
                    "minMessage" => "Votre message est trop court",
                    "max" => 500,
                    "maxMessage" => "Votre message est trop long"
                ])
            ]
        ])
        ->add("date", "datetime", [
            "format" => "YYYY/MM/DD HH:mm",
            "widget" => "single_text",
            "data" => new \DateTime("now"),
            "years" => range(date('Y')-10,date('Y')+10),
            "constraints" => [
                new Assert\DateTime([
                    "message" => "Date non valide"
                ])
            ]
        ])
        ->add("submit", "submit", [
            "label" => "Envoyer",
            "attr" => [
                "class" => "btn btn-default"
            ]
        ])
        ->getForm();

        $formFeedback->handleRequest($request);
        if ($formFeedback->isValid()) {
            $data = $formFeedback->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject($data["statut"])
                ->setFrom('feedback@example.com')
                ->setTo($data["mail"])
                //->setBody("du contenu")
                ->setBody($this->renderView('TroiswaBackBundle:Mails:feedback-email.html.twig', [
                    "data" => $data
                ]), 'text/html')
                ->addPart(
                    $this->renderView('TroiswaBackBundle:Mails:feedback-email.txt.twig', [
                        "data" => $data
                    ]), 'text/plain'
                );

            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add("success", "Votre signalement a bien été envoyé");

            $logger = $this->get('logger');
            $logger->info($data['statut'] . ": " . $data['description']);


            //return $this->redirect($this->generateUrl("troiswa_back_contact"));

            return $this->redirectToRoute("troiswa_back_feedback");
        }

        return $this->render("TroiswaBackBundle:Main:feedback.html.twig", [
            "formFeedback" => $formFeedback->createView()
        ]);
    }
}
