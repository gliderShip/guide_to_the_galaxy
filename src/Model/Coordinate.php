<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Coordinate
{
    /**
     * @Assert\PositiveOrZero()
     */
    private int $row;

    /**
     * @Assert\PositiveOrZero()
     */
    private int $column;

    public function __construct(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }
}
