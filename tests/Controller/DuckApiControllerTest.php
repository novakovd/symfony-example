<?php

namespace App\Tests\Controller;

use App\DataFixtures\ExampleDuck;
use App\Entity\Example\Duck;
use App\Repository\Example\DuckRepository;
use App\Tests\ApiTestTrait;
use GuzzleHttp\Utils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DuckApiControllerTest extends WebTestCase
{
    const CREATE_TEST_NAME = 'testName';
    const CREATE_TEST_COLOR = 'testColor';

    use ApiTestTrait;

    protected function assertSerializedEntity(array $data) {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('color', $data);

        $this->assertIsInt($data['id']);
        $this->assertIsString($data['name']);
        $this->assertIsString($data['color']);
    }

    protected function getRepository(): DuckRepository
    {
        /** @var $repo DuckRepository */
        $repo = self::getContainer()->get(DuckRepository::class);

        return $repo;
    }

    public function testFindEndpoint()
    {
        $client = $this->getClient();

        /** @var $duck Duck */
        $duck = $this->getRepository()->findOneBy(['name' => ExampleDuck::NAME]);

        $client->request('GET', '/api/v1/ducks/' . $duck->getId());

        $this->assertResponseIsSuccessful();

        $data = Utils::jsonDecode($client->getResponse()->getContent(), true);

        $this->assertSerializedEntity($data);

        $this->assertEquals($data['id'], $duck->getId());
        $this->assertEquals($data['name'], $duck->getName());
        $this->assertEquals($data['color'], $duck->getColor());
    }

    public function testFindAllEndpoint()
    {
        $client = $this->getClient();

        $client->request('GET', '/api/v1/ducks');

        $this->assertResponseIsSuccessful();

        $data = Utils::jsonDecode($client->getResponse()->getContent(), true);

        foreach ($data as $d) {
            $this->assertSerializedEntity($d);
        }
    }

    public function testCreateEndpoint()
    {
        $client = $this->getClient();

        $client->request('POST', '/api/v1/ducks', [], [], [], Utils::jsonEncode([
            'name' => self::CREATE_TEST_NAME,
            'color' => self::CREATE_TEST_COLOR
        ]));

        $this->assertResponseIsSuccessful();

        $data = Utils::jsonDecode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $data);
        $this->assertIsInt($data['id']);

        /** @var $duck Duck */
        $duck = $this->getRepository()->findOneBy(['id' => $data['id']]);

        $this->assertEquals($data['id'], $duck->getId());
        $this->assertEquals(self::CREATE_TEST_NAME, $duck->getName());
        $this->assertEquals(self::CREATE_TEST_COLOR, $duck->getColor());
    }
}