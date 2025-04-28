<?php

declare(strict_types=1);

namespace App\Presentation\Console\Commands\Users;

use App\Application\Features\Users\UsersFacade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/** @SuppressUnused */
#[AsCommand(name: 'wota:sandbox', description: 'Sandbox (play with code)', aliases: ['wota:sb'])]
class SandboxCommand extends Command
{
    public function __construct(private readonly UsersFacade $usersFacade)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'input',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Example: bin/console wota:sandbox --input=\'serialized:input\''
        );
    }

    protected function getUnserializedInput(InputInterface $input): ?array
    {
        if ($input->getOption('input') === null) {
            return null;
        }

        return unserialize($input->getOption('input'));
    }

    /**
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $unserializedInput = $this->getUnserializedInput($input);

        try {
            $this->usersFacade->create(...$unserializedInput);
            $output->writeln(["<info>User created</info>", '']);
        } catch (Throwable $e) {
            $output->writeln(["<error>Error: {$e->getMessage()}</error>", '']);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
