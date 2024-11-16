<?php

namespace App\Command;

use App\Entity\Utilisateur;
use App\Security\CustomRegexes;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validation;

#[AsCommand(
    name: "app:ajout-utilisateur",
    description: "Ajoute un nouvel utilisateur",
)]
class AjoutUtilisateurCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface      $entityManager,
        private readonly UtilisateurManagerInterface $utilisateurManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption("login", null, InputOption::VALUE_OPTIONAL, "Login de l'utilisateur")
            ->addOption("email", null, InputOption::VALUE_OPTIONAL, "Adresse email de l'utilisateur")
            ->addOption("password", null, InputOption::VALUE_OPTIONAL, "Mot de passe de l'utilisateur")
            ->addOption("visible", null, InputOption::VALUE_REQUIRED, "L'utilisateur est-il visible ? (yes/no) [no]")
            ->addOption("admin", null, InputOption::VALUE_REQUIRED, "L'utilisateur est-il admin ? (yes/no) [no]")
            ->addOption("codeUnique", null, InputOption::VALUE_OPTIONAL, "Code unique de l'utilisateur");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper("question");

        $login = $input->getOption("login") ?? $helper->ask($input, $output, new Question("Login de l'utilisateur: "));
        $email = $input->getOption("email") ?? $helper->ask($input, $output, new Question("Adresse email de l'utilisateur: "));
        $password = $input->getOption("password") ?? $helper->ask($input, $output, (new Question("Mot de passe de l'utilisateur: "))->setHidden(true));

        $visible = $input->getOption("visible");
        if (!$visible) {
            $visibleAnswer = $helper->ask($input, $output, new Question("L'utilisateur est-il visible ? (yes/no) [no]: ", "no"));
            $visible = in_array(strtolower($visibleAnswer), ["y", "yes"]) ? "yes" : "no";
        } else {
            $visible = in_array(strtolower($visible), ["y", "yes"]) ? "yes" : "no";
        }

        $admin = $input->getOption("admin");
        if (!$admin) {
            $adminAnswer = $helper->ask($input, $output, new Question("L'utilisateur est-il admin ? (yes/no) [no]: ", "no"));
            $admin = in_array(strtolower($adminAnswer), ["y", "yes"]) ? "yes" : "no";
        } else {
            $admin = in_array(strtolower($admin), ["y", "yes"]) ? "yes" : "no";
        }

        $codeUnique = $input->getOption("codeUnique") ?? $helper->ask($input, $output, new Question("Code unique de l'utilisateur (facultatif): ", ""));

        if ($this->utilisateurManager->checkFieldValidity("email", $email) != 404) {
            $output->writeln("Adresse mail invalide (déjà utilisée, ou ne respecte pas le format des e-mails)");
            return Command::FAILURE;
        }

        $user = new Utilisateur();
        $user->setLogin($login);
        $user->setEmail($email);
        $user->setVisible($visible === "yes");

        $regex = CustomRegexes::getRegexes()['password'];
        $validator = Validation::createValidator();
        if (count($validator->validate($password, $regex))) {
            $output->writeln($regex->message);
            return Command::FAILURE;
        }
        $this->utilisateurManager->processNewUtilisateur($user, $password);

        if ($admin === "yes") {
            $user->setRoles(array_merge($user->getRoles(), ["ROLE_ADMIN"]));
        }

        if ($codeUnique) {
            if ($this->utilisateurManager->checkFieldValidity("codeUnique", $codeUnique) != 404) {
                $output->writeln("Code unique invalide (déjà utilisé, ou ne respecte pas le format alphanumérique)");
                return Command::FAILURE;
            }
            $user->setCodeUnique($codeUnique);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("Utilisateur ajouté avec succès !");
        return Command::SUCCESS;
    }
}
