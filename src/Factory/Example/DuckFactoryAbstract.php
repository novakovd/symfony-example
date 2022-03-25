<?php

namespace App\Factory\Example;

use App\Entity\Example\Duck;

abstract class DuckFactoryAbstract
{
    public abstract function createDuck(array $duckData): Duck;
}