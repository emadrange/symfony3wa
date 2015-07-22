<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 22/07/15
 * Time: 10:59
 */

namespace Troiswa\BackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Troiswa\BackBundle\Entity\User;

class TroiswaUserCreateCommand extends ContainerAwareCommand {

    protected function configure() {

        $this->setName('troiswa:user:create')
            ->setDescription("Création d'un utilisateur avec mot de passe crypté")
            ->addArgument('pseudo', InputArgument::REQUIRED, 'Identifiant')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe')
            ->addOption('existe', null, InputOption::VALUE_NONE, 'Metre à jour un utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $factory = $this->getContainer()->get('security.encoder_factory');

        $style = new OutputFormatterStyle('yellow', 'red', ['bold']);
        $output->getFormatter()->setStyle('fire', $style);

        $option = $input->getOption('existe');

        if ($option) {
            $user = $em->getRepository('TroiswaBackBundle:User')
                ->findOneBy(['pseudo' => $input->getArgument('pseudo')]);

            if ($user) {

                $encoder = $factory->getEncoder($user);
                $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());

                $user->setPassword($newPassword);
                $em->persist($user);
                $em->flush();
                $output->writeln("<info>L'utilisateur " . $input->getArgument('pseudo') . " a bien été mis à jour</info>");
            } else {
                $output->writeln("<fire>L'utilisateur " . $input->getArgument('pseudo') . " n'existe pas</fire>");
            }

        } else {
            $user = new User();

            $encoder = $factory->getEncoder($user);
            $newPassword = $encoder->encodePassword($input->getArgument('password'), $user->getSalt());

            $user->setPseudo($input->getArgument('pseudo'));
            $user->setPassword($newPassword);
            $user->setAddress('bla');
            $user->setBirthday(new \DateTime('now'));
            $user->setEmail('mad@online.net');
            $user->setFirstname('Jean');
            $user->setLastname('Bon');
            $user->setPhone('+33654478951');
            $em->persist($user);
            $em->flush();

            $output->writeln("<info>L'utilisateur a bien été créé</info>");
        }
    }
}