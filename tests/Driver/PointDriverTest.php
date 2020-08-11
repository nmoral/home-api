<?php

namespace App\Tests\Driver;

use App\Driver\PointDriver;
use PHPUnit\Framework\TestCase;

class PointDriverTest extends TestCase
{
    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $points
     */
    public function testSave(array $points): void
    {
        $driver = new PointDriver();
        $actual = $driver->create($points);

        self::assertArrayHasKey('id', $actual);
        self::assertArrayHasKey('createdAt', $actual);
    }

    /**
     * @return \Generator<array<mixed>>
     */
    public function getCase(): \Generator
    {
        yield [
            [
                'name' => 'foo',
            ],
        ];
    }
}
