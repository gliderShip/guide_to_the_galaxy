<?php

namespace App\Model;

class Matrix
{
    public const BACKGROUND_ELEMENT_VALUE = 0;
    public const FOREGROUND_ELEMENT_VALUE = 1;
    public const VALID_INPUT_RANGE = [self::BACKGROUND_ELEMENT_VALUE, self::FOREGROUND_ELEMENT_VALUE];

    private array $matrix;

    private int $totalRows = 0;
    private int $totalColumns = 0;

    public function __construct(array $elements)
    {
        $this->totalRows = count($elements);
        $this->totalColumns = count(max($elements));

        foreach ($elements as &$row) {
            $row = array_pad($row, $this->totalColumns, self::BACKGROUND_ELEMENT_VALUE);
            $this->matrix[] = $row;
        }


    }

    /**
     * Returns false if the row is valid (contains only numbers), otherwise returns the index of the first invalid value.
     * @return false|int
     */
    public static function isIllegalRow(array $row)
    {
        foreach ($row as $index => $value) {
            if (!is_numeric($value) || !in_array($value, self::VALID_INPUT_RANGE)) {
                return $index;
            }
        }

        return false;
    }

    public static function isForegroundElement(int $value): bool
    {
        return $value === self::FOREGROUND_ELEMENT_VALUE;
    }

    public static function isBackgroundElement(int $value): bool
    {
        return $value === self::BACKGROUND_ELEMENT_VALUE;
    }

    public function getElement(Coordinate $coordinate): int
    {
        if (!isset($this->matrix[$coordinate->getRow()][$coordinate->getColumn()])) {
            throw new \Exception("Element not found at:" . $coordinate->getRow() . "x" . $coordinate->getColumn());
        }

        return $this->matrix[$coordinate->getRow()][$coordinate->getColumn()];
    }

    public function setElement(Coordinate $coordinate, int $value): void
    {
        if (!isset($this->matrix[$coordinate->getRow()][$coordinate->getColumn()])) {
            throw new \Exception("Element not found at:" . $coordinate->getRow() . "x" . $coordinate->getColumn());
        }

        $this->matrix[$coordinate->getRow()][$coordinate->getColumn()] = $value;
    }

    /**
     * @return array
     */
    public function getMatrix(): array
    {
        return $this->matrix;
    }

    /**
     * @return int
     */
    public function getTotalRows(): int
    {
        return $this->totalRows;
    }

    /**
     * @return int
     */
    public function getTotalColumns(): int
    {
        return $this->totalColumns;
    }


}
