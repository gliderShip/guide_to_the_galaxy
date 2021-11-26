<?php

namespace App\Model;

use App\Service\CoordinateManager;

interface AssociationStrategy
{
    /**
     * @return array|Coordinate[]
     */
    public function getAssociatedCellCoordinates(CoordinateManager $coordinateManager, Coordinate $position, int $totalRows, int $totalColumns): array;
}
