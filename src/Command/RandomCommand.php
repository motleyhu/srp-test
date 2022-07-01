<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

#[AsCommand(name: 'random')]
class RandomCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Example command for generating a random integer and outputting stuff about it')
            ->addArgument('min', InputArgument::REQUIRED, 'Minimum value')
            ->addArgument('max', InputArgument::REQUIRED, 'Maximum value')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Your name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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

        $wikiHtml = file_get_contents("https://en.wikipedia.org/wiki/{$random}_(number)");

        $crawler = new Crawler($wikiHtml);

        $output->writeln($crawler->filter('#bodyContent .mw-parser-output p')->first()->text());

        $output->writeln("Read more at https://en.wikipedia.org/wiki/{$random}_(number)");

        return self::SUCCESS;
    }

}
