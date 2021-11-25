<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;

class FourConnectivity implements AssociationStrategy
{
    /**
     * @return array|Coordinate[]
     */
    public function getConnectedCellCoordinates(CoordinateManager $coordinateManager, Coordinate $position, int $totalRows, int $totalColumns): array
    {
        $neighbourCoordinates = [];

        $top = $coordinateManager->getTop($position);
        $top ? $neighbourCoordinates[] = $top : null;
        $left = $coordinateManager->getLeft($position);
        $left ? $neighbourCoordinates[] = $left : null;
        $right = $coordinateManager->getRight($position, $totalColumns);
        $right ? $neighbourCoordinates[] = $right : null;
        $bottom = $coordinateManager->getBottom($position, $totalRows);
        $bottom ? $neighbourCoordinates[] = $bottom : null;

        return $neighbourCoordinates;
    }


}
