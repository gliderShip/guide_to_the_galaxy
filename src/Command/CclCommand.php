<?php

namespace App\Command;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;
use App\Service\CoordinateManagerInterface;
use App\Service\FourConnectivity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
    private AssociationStrategy $associationStrategy;
    private ?int $totalRows = null;
    private ?int $totalColumns = null;
    private int $groups = 0;
    private CoordinateManagerInterface $coordinateManager;

    public function __construct(FourConnectivity $associationStrategy, CoordinateManagerInterface $coordinateManager)
    {
        $this->associationStrategy = $associationStrategy;
        parent::__construct();
        $this->coordinateManager = $coordinateManager;
    }


    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $io->table([], self::$matrix);

        $this->totalRows = count(self::$matrix);
        $this->totalColumns = count(max(self::$matrix));

        $io->text(sprintf('Matrix %d x %d', $this->totalRows, $this->totalColumns));

        for ($currentRow = 0; $currentRow < $this->totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $this->totalColumns; $currentCol++) {
                if (self::$matrix[$currentRow][$currentCol] == 1) {
                    ++$this->groups;
                    $this->flood(self::$matrix, new Coordinate($currentCol, $currentRow));
                    $io->table([], self::$matrix);
                }
            }
        }

        $io->success(sprintf('Found %d groups', $this->groups));
        return 0;
    }

    private function flood(&$matrix, Coordinate $position)
    {
        $y = $position->getY();
        $x = $position->getX();

        if (!isset(self::$matrix[$y][$x]) || self::$matrix[$y][$x] == 0) {
            return;
        }

        self::$matrix[$y][$x] = 0;

        $connectedCells = $this->associationStrategy->getConnectedCellCoordinates($this->coordinateManager, $position, $this->totalRows, $this->totalColumns);
        foreach ($connectedCells as $neighbour) {
            $this->flood($matrix, $neighbour);
        }

        return;
    }


}
