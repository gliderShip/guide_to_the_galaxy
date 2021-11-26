<?php

namespace App\Service;

use App\Model\AssociationStrategy;
use App\Model\Coordinate;

class EightConnectivityStrategy implements AssociationStrategy
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

        $topLeft = $coordinateManager->getTopLeft($position);
        $topRight = $coordinateManager->getTopRight($position, $totalColumns);
        $bottomLeft = $coordinateManager->getBottomLeft($position, $totalRows);
        $bottomRight = $coordinateManager->getBottomRight($position, $totalRows, $totalColumns);

        $top ? $neighbourCoordinates[] = $top : null;
        $left ? $neighbourCoordinates[] = $left : null;
        $right ? $neighbourCoordinates[] = $right : null;
        $bottom ? $neighbourCoordinates[] = $bottom : null;
        $topLeft ? $neighbourCoordinates[] = $topLeft : null;
        $topRight ? $neighbourCoordinates[] = $topRight : null;
        $bottomLeft ? $neighbourCoordinates[] = $bottomLeft : null;
        $bottomRight ? $neighbourCoordinates[] = $bottomRight : null;

        return $neighbourCoordinates;
    }


}
