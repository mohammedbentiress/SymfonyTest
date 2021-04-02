<?php

namespace App\Util;

class Calculator
{
    /**
     * A simple methode that returns the sum of two floats
     * @param float $a
     * @param float $b
     * @return float
     */
    public function add(float $a, float $b): float
    {
        return $a + $b;
    }
}
