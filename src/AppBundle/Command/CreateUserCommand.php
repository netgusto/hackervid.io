<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\OutputInterface;

use ModelBundle\Entity\User;

class CreateUserCommand extends ContainerAwareCommand {
    protected function configure() {
        $this
            ->setName('app:user:create')
            ->setDescription('Create user')
            ->setDefinition(
                new InputDefinition(array(
                   new InputOption('username', null, InputOption::VALUE_REQUIRED, 'Username; required'),
                   new InputOption('password', null, InputOption::VALUE_REQUIRED, 'Password; required'),
                ))
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $passwordencoder = $this->getContainer()->get('security.password_encoder');
        $em = $this->getContainer()->get('em');

        $username = $input->getOption('username');
        if(is_null($username)) throw new \Exception("--username is required.");

        $password = $input->getOption('password');
        if(is_null($password)) throw new \Exception("--password is required.");

        $user = (new User())
            ->setUsername($username)
            ->setCreationDate(new \DateTime())
            ->setKarma(1);

        $user->setPasswordHash($passwordencoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();

        $output->writeLn("User created.");
    }
}
