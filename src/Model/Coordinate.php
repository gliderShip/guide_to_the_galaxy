<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Coordinate
{
    /**
     * @Assert\PositiveOrZero()
     */
    private int $x;

    /**
     * @Assert\PositiveOrZero()
     */
    private int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }




}
