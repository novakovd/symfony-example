<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class SerializerService
{
    public function getDefaultSerializer(): Serializer
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }
}