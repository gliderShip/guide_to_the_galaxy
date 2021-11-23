<?php

namespace App\Service;

use App\Model\Coordinate;

interface CoordinateManagerInterface
{
    public function getRight(Coordinate $coordinate, int $totalColumns): ?Coordinate;

    public function getTop(Coordinate $coordinate): ?Coordinate;

    public function getLeft(Coordinate $coordinate): ?Coordinate;

    public function getBottom(Coordinate $coordinate, int $totalRows): ?Coordinate;

    public function getTopLeft(Coordinate $coordinate): ?Coordinate;

    public function getTopRight(Coordinate $coordinate, int $totalColumns): ?Coordinate;

    public function getBottomLeft(Coordinate $coordinate, int $totalRows): ?Coordinate;

    public function getBottomRight(Coordinate $coordinate, int $totalRows, int $totalColumns): ?Coordinate;


    public function isFirstRow(Coordinate $coordinate): bool;

    public function isLastRow(Coordinate $coordinate, int $totalRows): bool;

    public function isFirstColumn(Coordinate $coordinate): bool;

    public function isLastColumn(Coordinate $coordinate, int $totalColumns): bool;
}
