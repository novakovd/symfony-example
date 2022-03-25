<?php

namespace App\Factory\Example;

use App\Entity\Example\Duck;

class DuckFactory extends DuckFactoryAbstract
{
    public function createDuck(array $duckData): Duck
    {
        return
            (new Duck())
            ->setName($duckData['name'] ?? null)
            ->setColor($duckData['color'] ?? null);
    }
}