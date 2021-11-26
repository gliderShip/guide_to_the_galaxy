<?php

namespace App\Service;

use App\Model\Coordinate;

class CoordinateManager implements CoordinateManagerInterface
{
    public function isFirstRow(Coordinate $coordinate): bool
    {
        return $coordinate->getRow() === 0;
    }

    public function isFirstColumn(Coordinate $coordinate): bool
    {
        return $coordinate->getColumn() === 0;
    }

    public function isLastRow(Coordinate $coordinate, int $totalRows): bool
    {
        return $coordinate->getRow() === $totalRows - 1;
    }

    public function isLastColumn(Coordinate $coordinate, int $totalColumns): bool
    {
        return $coordinate->getColumn() === $totalColumns - 1;
    }

    public function getTop(Coordinate $coordinate): ?Coordinate
    {
        if ($this->isFirstRow($coordinate)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() - 1, $coordinate->getColumn());
    }

    public function getLeft(Coordinate $coordinate): ?Coordinate
    {
        if ($this->isFirstColumn($coordinate)) {
            return null;
        }

        return new Coordinate($coordinate->getRow(), $coordinate->getColumn() - 1);
    }

    public function getRight(Coordinate $coordinate, int $totalColumns): ?Coordinate
    {
        if ($this->isLastColumn($coordinate, $totalColumns)) {
            return null;
        }

        return new Coordinate($coordinate->getRow(), $coordinate->getColumn() + 1);
    }

    public function getBottom(Coordinate $coordinate, int $totalRows): ?Coordinate
    {
        if ($this->isLastRow($coordinate, $totalRows)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() + 1, $coordinate->getColumn());
    }

    public function getTopLeft(Coordinate $coordinate): ?Coordinate
    {
        if ($this->isFirstRow($coordinate) || $this->isFirstColumn($coordinate)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() - 1, $coordinate->getColumn() - 1);
    }

    public function getTopRight(Coordinate $coordinate, int $totalColumns): ?Coordinate
    {
        if ($this->isFirstRow($coordinate) || $this->isLastColumn($coordinate, $totalColumns)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() - 1, $coordinate->getColumn() + 1);
    }

    public function getBottomLeft(Coordinate $coordinate, int $totalRows): ?Coordinate
    {
        if ($this->isLastRow($coordinate, $totalRows) || $this->isFirstColumn($coordinate)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() + 1, $coordinate->getColumn() - 1);
    }

    public function getBottomRight(Coordinate $coordinate, int $totalRows, int $totalColumns): ?Coordinate
    {
        if ($this->isLastRow($coordinate, $totalRows) || $this->isLastColumn($coordinate, $totalColumns)) {
            return null;
        }

        return new Coordinate($coordinate->getRow() + 1, $coordinate->getColumn() + 1);
    }

}

