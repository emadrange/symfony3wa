<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 10/07/15
 * Time: 15:43
 */

namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QuantityProductCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('product:quantity')
            ->setDescription("Permet d'envoyer un mail des produits dont la quantité est inférieur à 5")
            ->addArgument('nombre', InputArgument::OPTIONAL, 'Quantité demandé ?')
            ->addOption('message', '-m', InputOption::VALUE_NONE,
                'Si définie, un petit message apparaitra'
            );
  }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $templating = $this->getContainer()->get('templating');

        $nombre = $input->getArgument('nombre');

        //dump($nombre);
        //die();

        if ($nombre) {
            $products = $em->getRepository("TroiswaBackBundle:Product")->findProductsByMinimumQuantity($nombre);

            $message = \Swift_Message::newInstance()
                ->setSubject("Produits avec quantité inférieur à 5")
                ->setFrom('ericmadrange@gmail.com')
                ->setTo('ericmadrange@gmail.com')
                //->setBody("du contenu")
                ->setBody($templating->render('TroiswaBackBundle:Mails:quantity-products-email.html.twig', [
                    "products" => $products
                ]), 'text/html')
                ->addPart(
                    $templating->render('TroiswaBackBundle:Mails:quantity-products-email.txt.twig', [
                        "products" => $products
                    ]), 'text/plain'
                );

            $this->getContainer()->get('mailer')->send($message);
            $output->writeln("Votre mail est bien envoyé");

        }
        $option = $input->getOption("message");
        if ($option) {
            $output->writeln('<fg=yellow>Un autre message</fg=yellow>');
        }

    }
}
