<?php

namespace App\Command;

use Symfony\Component\Console\Cursor;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use App\Service;
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
            'Ask',
            new IODumper\FormattedIODumper(
                '<bg=black;fg=yellow>%s</>',
            ),
        );

		if ($this->isOk()) {
			$this->ioDump(
				'OK1 answer',
				new IODumper\FormattedIODumper(
					'<bg=black;fg=yellow>%s</>',
				),
			);			
		} else {
			$this->ioDump(
				'NEGATIVE1 answer',
				new IODumper\FormattedIODumper(
					'<bg=black;fg=yellow>%s</>',
				),
			);
		}
		
		if ($this->isOk()) {
			$this->ioDump(
				'OK2 answer',
				new IODumper\FormattedIODumper(
					'<bg=black;fg=yellow>%s</>',
				),
			);			
		} else {
			$this->ioDump(
				'NEGATIVE2 answer',
				new IODumper\FormattedIODumper(
					'<bg=black;fg=yellow>%s</>',
				),
			);
		}
		
        return Command::SUCCESS;
    }
}
