<?php

namespace App\DataFixtures;

use App\Factory\Example\DuckFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExampleDuck extends Fixture
{
    const NAME = 'fixtureDuck';
    const COLOR = 'fixtureDuckColor';

    private DuckFactory $duckFactory;

    public function __construct(DuckFactory $duckFactory)
    {
        $this->duckFactory = $duckFactory;
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->duckFactory->createDuck(['name' => self::NAME, 'color' => self::COLOR]));
        $manager->flush();
    }
}
