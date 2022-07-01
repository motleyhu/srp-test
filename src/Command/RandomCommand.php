<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;

#[AsCommand(name: 'random')]
class RandomCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument('min', InputArgument::REQUIRED)
            ->addArgument('max', InputArgument::REQUIRED)
            ->addOption('name', InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello '.$input->getOption('name'));

        $random = random_int(
            $input->getArgument('min'),
            $input->getArgument('max')
        );
        $output->writeln("Your number is {$random}\n");

        $divisors = [];
        for ($i = 1; $i <= $random; $i++) {
            if ($random % $i == 0) {
                $divisors[] = $i;
            }
        }

        $output->writeln("Its divisors are " . implode(',', $divisors));

        if (count(array_diff($divisors, [1, $random])) === 0) {
            $output->writeln('And it is prime');
        } else {
            $output->writeln('And it is not a prime');
        }

        return self::SUCCESS;
    }

}