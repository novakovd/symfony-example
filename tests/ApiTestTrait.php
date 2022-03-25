<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @method static KernelBrowser createClient()
 */
trait ApiTestTrait
{
    public function getClient(): KernelBrowser
    {
        $client = self::createClient();

        $client->followRedirects();
        $client->setServerParameter('HTTP_HOST', 'nginx:8010');

        return $client;
    }
}