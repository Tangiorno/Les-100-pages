<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:ajout-utilisateur',
    description: 'Ajoute un nouvel utilisateur',
)]
class AjoutUtilisateurCommand extends Command
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, "Login de l'utilisateur")
            ->addArgument('email', InputArgument::REQUIRED, "Adresse email de l'utilisateur")
            ->addArgument('password', InputArgument::REQUIRED, "Mot de passe de l'utilisateur")
            ->addOption('visible', null, InputOption::VALUE_NONE, "L'utilisateur est-il visible ?")
            ->addOption('admin', null, InputOption::VALUE_NONE, "L'utilisateur est-il admin ?")
            ;//->$this->addOption('codeUnique', null, InputOption::VALUE_REQUIRED, "Code unique de l'utilisateur");
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $login = $input->getArgument('login');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $visible = $input->getOption('visible');
        $admin = $input->getOption('admin');

        $user = new Utilisateur();
        $user->setLogin($login);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setVisible($visible);
        $user->setCodeUnique(uniqid());
        $user->setDateConnexion(new \DateTime());
        $user->setProfil(false);
        $user->setDateEdition(new \DateTime());
        if ($admin) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_ADMIN']));
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur ajouté avec succès !');
        return Command::SUCCESS;
    }

}
