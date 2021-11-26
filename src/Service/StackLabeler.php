<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;
use App\Model\Matrix;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Style\SymfonyStyle;

class StackLabeler extends Labeler implements ConnectedComponentLabeler
{
    private array $stack = [];

    private int $totalRows;
    private int $totalColumns;

    public function getGroupsNr(Matrix $matrix, ?SymfonyStyle $logger = null): int
    {
        $this->totalRows = $matrix->getTotalRows();
        $this->totalColumns = $matrix->getTotalColumns();
        $groups = 0;

        for ($currentRow = 0; $currentRow < $this->totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $this->totalColumns; $currentCol++) {
                $coordinate = new Coordinate($currentRow, $currentCol);
                if (Matrix::isForegroundElement($matrix->getElement($coordinate))) {
                    ++$groups;
                    $matrix->setElement($coordinate, Matrix::BACKGROUND_ELEMENT_VALUE);
                    array_push($this->stack, new Coordinate($currentRow, $currentCol));
                    $this->flood($this->stack, $matrix);
                    if ($logger && $logger->isVerbose()) {
                        $logger->writeln(sprintf('Found group %d', $groups));
                        $logger->table([new TableCell("Flooded Matrix (iteration=$groups)", ['colspan' => $this->totalColumns])], $matrix->getMatrix());
                    }
                }
            }
        }

        return $groups;
    }

    private function flood(array &$stack, Matrix &$matrix)
    {
        while ($coordinate = array_pop($stack)) {
            $associatedCellCoordinates = $this->associationStrategy->getAssociatedCellCoordinates($this->coordinateManager, $coordinate, $this->totalRows, $this->totalColumns);
            foreach ($associatedCellCoordinates as $neighbourCoordinate) {
                if (Matrix::isForegroundElement($matrix->getElement($neighbourCoordinate))) {
                    array_push($stack, $neighbourCoordinate);
                    $matrix->setElement($neighbourCoordinate, Matrix::BACKGROUND_ELEMENT_VALUE);
                }
            }
        }
    }
}
