<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Service\UtilisateurManagerInterface;
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
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly UtilisateurManagerInterface $utilisateurManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED, "Login de l'utilisateur")
            ->addArgument('email', InputArgument::REQUIRED, "Adresse email de l'utilisateur")
            ->addArgument('password', InputArgument::REQUIRED, "Mot de passe de l'utilisateur")
            ->addOption('visible', null, InputOption::VALUE_NONE, "L'utilisateur est-il visible ?")
            ->addOption('admin', null, InputOption::VALUE_NONE, "L'utilisateur est-il admin ?")
            ->addOption('codeUnique', null, InputOption::VALUE_REQUIRED, "Code unique de l'utilisateur");
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $login = $input->getArgument('login');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $visible = $input->getOption('visible');
        $admin = $input->getOption('admin');
        $codeUnique = $input->getOption('codeUnique');

        $user = new Utilisateur();
        $user->setLogin($login);
        if ($this->utilisateurManager->checkFieldNotTakenNormal("email", $email)) {
            $output->writeln('Adresse mail déjà utilisée');
            return Command::FAILURE;
        }
        $user->setEmail($email);
        $user->setVisible($visible);
        $this->utilisateurManager->processNewUtilisateur($user, $password);
        if ($admin) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_ADMIN']));
        }
        if ($codeUnique) {
            if ($this->utilisateurManager->checkFieldNotTakenNormal("codeUnique", $codeUnique)) {
                $output->writeln('Code unique déjà utilisé');
                return Command::FAILURE;
            }
            $user->setCodeUnique($codeUnique);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur ajouté avec succès !');
        return Command::SUCCESS;
    }

}
