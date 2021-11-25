<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Style\SymfonyStyle;

class CclManager
{
    private AssociationStrategy $associationStrategy;
    private CoordinateManagerInterface $coordinateManager;

    private ?int $totalRows = null;
    private ?int $totalColumns = null;

    public function __construct(FourConnectivity $associationStrategy, CoordinateManagerInterface $coordinateManager)
    {
        $this->associationStrategy = $associationStrategy;
        $this->coordinateManager = $coordinateManager;
    }

    public function getGroups(array $matrix, ?SymfonyStyle $logger=null): int
    {
        $groups = 0;
        $this->totalRows = count($matrix);
        $this->totalColumns = count(max($matrix));

        for ($currentRow = 0; $currentRow < $this->totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $this->totalColumns; $currentCol++) {
                if ($matrix[$currentRow][$currentCol] == 1) {
                    ++$groups;
                    $this->flood($matrix, new Coordinate($currentCol, $currentRow));
                    if($logger && $logger->isVerbose()) {
                        $logger->writeln(sprintf('Found group %d', $groups));
                        $logger->table([new TableCell("Flooded Matrix (iteration=$groups)", ['colspan' => $this->totalColumns])], $matrix);
                    }
                }
            }
        }

        return $groups;
    }

    /**
     * Warning! This function has side effects. The matrix array parameter will be destroyed.
     * Please make a copy of the original matrix array if you need to keep it.
     */
    private function flood(&$matrix, Coordinate $position)
    {
        $y = $position->getY();
        $x = $position->getX();

        if (!isset($matrix[$y][$x]) || $matrix[$y][$x] == 0) {
            return;
        }

        $matrix[$y][$x] = 0;

        $connectedCells = $this->associationStrategy->getConnectedCellCoordinates($this->coordinateManager, $position, $this->totalRows, $this->totalColumns);
        foreach ($connectedCells as $neighbour) {
            $this->flood($matrix, $neighbour);
        }
    }
}
