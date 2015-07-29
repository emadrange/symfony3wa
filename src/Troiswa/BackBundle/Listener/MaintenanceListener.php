<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 29/07/15
 * Time: 17:38
 */

// documentation : http://symfony.com/doc/current/components/http_kernel/introduction.html
// Rechercher KernelEvents::REQUEST

namespace Troiswa\BackBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{

    private $maintenance;

    private $twig;

    public function __construct($paramMaintenance, \Twig_Environment $twig)
    {
        $this->maintenance = $paramMaintenance;
        $this->twig = $twig;
    }

    public function onMaintenance(GetResponseEvent $event)
    {
        //dump($this->maintenance);
        //die;

        if($this->maintenance == true)
        {
            $content = $this->twig->render('TroiswaBackBundle:Maintenance:index.html.twig');
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}