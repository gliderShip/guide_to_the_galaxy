<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Style\SymfonyStyle;

class CclManagerIterative
{
    private AssociationStrategy $associationStrategy;
    private CoordinateManagerInterface $coordinateManager;

    private ?int $totalRows = null;
    private ?int $totalColumns = null;

    private array $stack = [];

    public function __construct(FourConnectivity $associationStrategy, CoordinateManagerInterface $coordinateManager)
    {
        $this->associationStrategy = $associationStrategy;
        $this->coordinateManager = $coordinateManager;
    }

    public function getGroups(array $matrix, ?SymfonyStyle $logger = null): int
    {
        $groups = 0;
        $this->totalRows = count($matrix);
        $this->totalColumns = count(max($matrix));

        for ($currentRow = 0; $currentRow < $this->totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $this->totalColumns; $currentCol++) {
                if ($matrix[$currentRow][$currentCol] == 1) {
                    ++$groups;
                    array_push($this->stack, new Coordinate($currentRow, $currentCol));
                    $this->flood($this->stack, $matrix);
                    if ($logger && $logger->isVerbose()) {
                        $logger->writeln(sprintf('Found group %d', $groups));
                        $logger->table([new TableCell("Flooded Matrix (iteration=$groups)", ['colspan' => $this->totalColumns])], $matrix);
                    }
                }
            }
        }

        return $groups;
    }

    private function flood(array &$stack, array &$matrix, int $groups)
    {
        $coordinate = array_pop($stack);
        if ($coordinate) {
            $connectedCells = $this->associationStrategy->getConnectedCellCoordinates($this->coordinateManager, $coordinate, $this->totalRows, $this->totalColumns);
            foreach ($connectedCells as $neighbourCoordinates) {
                if ($this->getMatrixElement($matrix, $neighbourCoordinates) == 1) {
                    array_push($stack, $neighbourCoordinates);
                    $this->setMatrixElement($matrix, $neighbourCoordinates, 0);
                }
            }
        }
    }

    private function getMatrixElement(array &$matrix, Coordinate $coordinate): int
    {
        return $matrix[$coordinate->getRow()][$coordinate->getColumn()];
    }

    private function setMatrixElement(array &$matrix, Coordinate $coordinate, int $value): void
    {
        $matrix[$coordinate->getRow()][$coordinate->getColumn()] = $value;
    }
}
