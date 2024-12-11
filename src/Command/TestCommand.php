<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'test',
    description: '',
    hidden: true,
)]
class TestCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface     $logger,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Однажды была выполнена команда');

        $io->info('https://google.com');

        $stderr = $io->getErrorStyle();

        $stderr->error('ERRORRRORORORORORO');

        return Command::SUCCESS;
    }
}
