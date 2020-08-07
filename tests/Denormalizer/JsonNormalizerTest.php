<?php

namespace App\Tests\Denormalizer;

use App\Normalizer\JsonNormalizer;
use PHPUnit\Framework\TestCase;

class JsonNormalizerTest extends TestCase
{
    /**
     * @dataProvider getCase
     */
    public function testDenormalize(string $json): void
    {
        $jsonDenormalizer = new JsonNormalizer();

        $actual = $jsonDenormalizer->denormalize($json);

        self::assertIsArray($actual);
    }

    /**
     * @return \Generator<array<int, string>>
     */
    public function getCase(): \Generator
    {
        yield [
            '{"foo": "bar"}',
        ];
    }
}
