<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;

class FourConnectivityStrategy implements AssociationStrategy
{
    /**
     * @return array|Coordinate[]
     */
    public function getAssociatedCellCoordinates(CoordinateManager $coordinateManager, Coordinate $position, int $totalRows, int $totalColumns): array
    {
        $neighbourCoordinates = [];

        $top = $coordinateManager->getTop($position);
        $left = $coordinateManager->getLeft($position);
        $right = $coordinateManager->getRight($position, $totalColumns);
        $bottom = $coordinateManager->getBottom($position, $totalRows);

        $top ? $neighbourCoordinates[] = $top : null;
        $left ? $neighbourCoordinates[] = $left : null;
        $right ? $neighbourCoordinates[] = $right : null;
        $bottom ? $neighbourCoordinates[] = $bottom : null;

        return $neighbourCoordinates;
    }


}
