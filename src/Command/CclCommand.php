<?php

namespace App\Command;

use App\Service\CclManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CclCommand extends Command
{
    protected static $defaultName = 'app:ccl';
    protected static string $defaultDescription = 'Connected-component labeling';

    private static array $matrix = [
        [1, 1, 0, 0, 0],
        [0, 1, 0, 0, 1],
        [1, 0, 0, 1, 1],
        [1, 0, 0, 0, 0],
        [1, 0, 1, 0, 1],
        [0, 0, 0, 1, 1],
    ];


    private ?int $totalRows = null;
    private ?int $totalColumns = null;
    private int $groups = 0;
    private CclManager $cclManager;

    public function __construct(CclManager $cclManager)
    {
        parent::__construct();
        $this->cclManager = $cclManager;
    }

    protected function configure(): void
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->text(sprintf('Matrix %d x %d', $this->totalRows, $this->totalColumns));
        $io->table([], self::$matrix);

        $this->totalRows = count(self::$matrix);
        $this->totalColumns = count(max(self::$matrix));

        $this->groups = $this->cclManager->getGroups(self::$matrix, $io);


        $io->success(sprintf('Found %d groups', $this->groups));
        return 0;
    }


}
