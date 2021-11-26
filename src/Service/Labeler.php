<?php

namespace App\Service;

use App\Model\AssociationStrategy;

abstract class Labeler
{
    protected AssociationStrategy $associationStrategy;
    protected CoordinateManagerInterface $coordinateManager;

    public function __construct(FourConnectivityStrategy $associationStrategy, CoordinateManagerInterface $coordinateManager)
    {
        $this->associationStrategy = $associationStrategy;
        $this->coordinateManager = $coordinateManager;
    }

}
