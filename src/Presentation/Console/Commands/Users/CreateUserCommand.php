<?php

declare(strict_types=1);

namespace App\Presentation\Console\Commands\Users;

use App\Application\Features\Users\CreateUserInvoker;
use App\Domain\Entities\Users\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Throwable;

/** @SuppressUnused */
#[AsCommand(name: 'wota:users:create', description: 'Create a new user', aliases: ['wota:uc'])]
class CreateUserCommand extends Command
{
    public function __construct(private readonly CreateUserInvoker $createUserInvoker)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED);
        $this->addArgument(
            'roles',
            InputArgument::IS_ARRAY,
            'Comma-separated roles for the user (e.g.: ROLE_USER,ROLE_ADMIN)',
            default: [User::ROLE_USER],
        );
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ['email' => $email, 'roles' => $roles] = $input->getArguments();
        $question = (new Question('<comment>Password:</comment>'))->setHidden(true)->setHiddenFallback(false);
        $password = (new QuestionHelper())->ask($input, $output, $question);
        $maskedPassword = str_repeat('*', strlen($password));
        $rolesString = implode(', ', $roles);

        $output->writeln('<info>Creating User...<info>');
        $output->writeln("<comment>Email:</comment> <fg=cyan>$email</>");
        $output->writeln("<comment>Password:</comment> <fg=cyan>$maskedPassword</>");
        $output->writeln("<comment>Roles:</comment> <fg=cyan>$rolesString</>");

        try {
            $this->createUserInvoker->__invoke($email, $password, $roles);
            $output->writeln(["<info>User created</info>", '']);
        } catch (Throwable $e) {
            $output->writeln(["<error>Error: {$e->getMessage()}</error>", '']);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
