<?php

namespace App\Command;

use App\Model\Matrix;
use App\Service\ConnectedComponentLabeler;
use App\Service\RecursiveLabeler;
use App\Service\StackLabeler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class StaticLabelerCommand extends Command
{
    protected static $defaultName = 'app:static-labeler';
    protected static string $defaultDescription = 'Connected-component labeling';

    private static array $elements = [
        [1, 1, 0, 0, 0],
        [0, 1, 0, 0, 1],
        [1, 0, 0, 1, 1],
        [1, 0, 0, 0, 0],
        [1, 0, 1, 0, 1],
        [0, 0, 0, 1, 1],
    ];


    private ConnectedComponentLabeler $labeler;

    public function __construct(RecursiveLabeler $labeler)
    {
        parent::__construct();
        $this->labeler = $labeler;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $matrix = new Matrix(self::$elements);


        $io->text(sprintf('Matrix %d rows x %d columns', $matrix->getTotalRows(), $matrix->getTotalColumns()));
        $io->table([], self::$elements);

        $groups = $this->labeler->getGroupsNr($matrix, $io);


        $io->success(sprintf('Found %d groups', $groups));
        return 0;
    }


}
