<?php

namespace App\Command;

use GrinWay\Command\Contracts\IO as IODumper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'test',
    hidden: true,
)]
class TestCommand extends AbstractCommand
{
    public const HELP = 'JUST A TEST COMMAND';
    public const DESCRIPTION = 'JUST A TEST COMMAND';

    protected function command(
        InputInterface $input,
        OutputInterface $output,
    ): int {

        $this->ioDump(
            'Продолжить?',
            new IODumper\FormattedIODumper(
                '<bg=black;fg=yellow>%s</>',
            ),
        );

        if ($this->isOk()) {
            $this->ioDump(
                'Продолжено',
                new IODumper\FormattedIODumper(
                    '<bg=black;fg=green>%s</>',
                ),
            );
            return Command::SUCCESS;
        } else {
            $this->ioDump(
                'Отменено',
                new IODumper\FormattedIODumper(
                    '<bg=black;fg=red>%s</>',
                ),
            );
            return Command::INVALID;
        }

        return Command::FAILURE;
    }
}
