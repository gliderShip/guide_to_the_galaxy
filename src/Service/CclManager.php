<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;
use Psr\Log\LoggerInterface;

class CclManager
{
    private AssociationStrategy $associationStrategy;
    private CoordinateManagerInterface $coordinateManager;

    private ?int $totalRows = null;
    private ?int $totalColumns = null;
    private LoggerInterface $logger;

    public function __construct(AssociationStrategy $associationStrategy, CoordinateManagerInterface $coordinateManager, LoggerInterface $logger)
    {
        $this->associationStrategy = $associationStrategy;
        $this->coordinateManager = $coordinateManager;
        $this->logger = $logger;
    }

    public function getGroups(array $matrix)
    {

        $groups = 0;

        for ($currentRow = 0; $currentRow < $this->totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $this->totalColumns; $currentCol++) {
                if ($matrix[$currentRow][$currentCol] == 1) {
                    ++$groups;
                    $this->flood($matrix, new Coordinate($currentCol, $currentRow));
                    $this->logger->debug(sprintf('Group %d flooded', $groups));
                    $this->logger->debug("Flooded Matrix", [$matrix]);
                }
            }
        }
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

        return;
    }
}
