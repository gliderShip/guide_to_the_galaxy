<?php

namespace App\Service;

use App\Model\Coordinate;
use App\Model\Matrix;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Style\SymfonyStyle;

class RecursiveLabeler extends Labeler implements ConnectedComponentLabeler
{
    public function getGroupsNr(Matrix $matrix, ?SymfonyStyle $logger = null): int
    {
        $groups = 0;
        $totalRows = $matrix->getTotalRows();
        $totalColumns = $matrix->getTotalColumns();

        for ($currentRow = 0; $currentRow < $totalRows; $currentRow++) {
            for ($currentCol = 0; $currentCol < $totalColumns; $currentCol++) {
                $currentCoordinate = new Coordinate($currentRow, $currentCol);
                if (Matrix::isForegroundElement($matrix->getElement($currentCoordinate))) {
                    ++$groups;
                    $this->flood($matrix, $currentCoordinate);
                    if ($logger && $logger->isVerbose()) {
                        $logger->writeln(sprintf('Found group %d', $groups));
                        $logger->table([new TableCell("Flooded Matrix (iteration=$groups)", ['colspan' => $matrix->getTotalColumns()])], $matrix->getMatrix());
                    }
                }
            }
        }

        return $groups;
    }

    /**
     * Warning! This function has side effects. The matrix will be destroyed.
     * Please make a copy of the original matrix if you need to keep it.
     */
    private function flood(Matrix &$matrix, Coordinate $position)
    {
        if (Matrix::isBackgroundElement($matrix->getElement($position))) {
            return;
        }

        $matrix->setElement($position, Matrix::BACKGROUND_ELEMENT_VALUE);

        $connectedCells = $this->associationStrategy->getAssociatedCellCoordinates($this->coordinateManager, $position, $matrix->getTotalRows(), $matrix->getTotalColumns());
        foreach ($connectedCells as $neighbour) {
            $this->flood($matrix, $neighbour);
        }
    }
}
