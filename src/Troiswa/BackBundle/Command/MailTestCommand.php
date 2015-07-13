<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 13/07/15
 * Time: 09:10
 */

namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MailTestCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName("mail:test")
            ->setDescription("Test d'envoie de mail");

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $message = \Swift_Message::newInstance()
            ->setSubject("Mail test")
            ->setFrom('ericmadrange@gmail.com')
            ->setTo('ericmadrange@gmail.com')
            ->setBody("Message du mail test");

        $this->getContainer()->get('mailer')->send($message);
        $output->writeln("Le mail est bien envoyé");

    }
}