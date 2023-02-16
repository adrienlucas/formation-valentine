<?php

namespace App\Command;

use App\UseCase\ListMoviesUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsCommand(
    name: 'app:movie:list',
    description: 'Add a short description for your command',
)]
class MovieListCommand extends Command
{
    public function __construct(
        private ListMoviesUseCase $usecase,
        private NormalizerInterface $normalizer,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('page', null, InputOption::VALUE_REQUIRED, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $movies = ($this->usecase)($input->getOption('page') ?? 1);
        $io->table(
            ['id', 'title', 'description', 'releaseDate', 'genres'],
            $this->normalizer->normalize($movies, context: ['groups' => ['cli']])
        );

        return Command::SUCCESS;
    }
}