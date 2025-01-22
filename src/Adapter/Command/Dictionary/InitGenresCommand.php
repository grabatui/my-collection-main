<?php

declare(strict_types=1);

namespace App\Adapter\Command\Dictionary;

use App\Domain\Entity\Genre;
use App\Domain\Repository\GenreRepository;
use App\Domain\Service\Dictionary\Loader\DictionaryLoaderInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:dictionary:init:genres',
)]
class InitGenresCommand extends Command
{
    public function __construct(
        private readonly GenreRepository $genreRepository,
        private readonly DictionaryLoaderInterface $dictionaryLoader,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $seriesGenres = $this->dictionaryLoader->getSeriesGenres();
        $movieGenres = $this->dictionaryLoader->getMovieGenres();

        if (empty($seriesGenres) && empty($movieGenres)) {
            return self::FAILURE;
        }

        $remoteGenresByExternalId = [];
        foreach ($seriesGenres as $genre) {
            $remoteGenresByExternalId[$genre->id] = $genre;
        }
        foreach ($movieGenres as $genre) {
            $remoteGenresByExternalId[$genre->id] = $genre;
        }

        $alreadyExists = $this->genreRepository->findByExternalIds(
            array_keys($remoteGenresByExternalId),
        );

        foreach ($remoteGenresByExternalId as $externalId => $genre) {
            $name = mb_strtoupper(mb_substr($genre->name, 0, 1)) . mb_substr($genre->name, 1);

            if (isset($alreadyExists[$externalId])) {
                $entity = $alreadyExists[$externalId];

                $entity->setName($name);
            } else {
                $entity = Genre::create(
                    externalId: $externalId,
                    name: $name,
                );
            }

            $this->genreRepository->save($entity);
        }

        return self::SUCCESS;
    }
}
