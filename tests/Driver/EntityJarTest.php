<?php

namespace App\Tests\Driver;

use App\Driver\EntityJar;
use PHPUnit\Framework\TestCase;

class EntityJarTest extends TestCase
{
    private ?EntityJar $jar;

    protected function setUp(): void
    {
        $this->jar = new EntityJar();
        [
            $addedEntity,
            $expectedEntity,
            $existentEntity
        ] = $this->getProvidedData();

        if (null === $existentEntity) {
            return;
        }

        $this->jar->add($existentEntity);
    }

    protected function tearDown(): void
    {
        $this->jar = null;
    }

    /**
     * @dataProvider getAddCase
     *
     * @param array<string, mixed> $entity
     * @param array<string, mixed> $expected
     */
    public function testAdd(array $entity, array $expected): void
    {
        $actual = $this->jar->add($entity);

        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @return \Generator<array<int, array<string, string|mixed>|null>>
     */
    public function getAddCase(): \Generator
    {
        yield [
            [
                'id' => '123456',
                'name' => 'foo',
            ],
            [
                'id' => '123456',
                'name' => 'foo',
            ],
            null,
        ];

        yield [
            [
                'id' => '123456',
                'name' => 'foo',
            ],
            [
                'id' => '123456',
                'name' => 'foo',
                'bar' => 'foo',
            ],
            [
                'id' => '123456',
                'bar' => 'foo',
            ],
        ];

        yield [
            [
                'id' => '123456',
                'name' => 'foo',
                'points' => [
                    '1234567' => [
                        'id' => '1234567',
                        'name' => 'points',
                    ],
                ],
            ],
            [
                'id' => '123456',
                'name' => 'foo',
                'bar' => 'foo',
                'points' => [
                   '1234568' => [
                        'id' => '1234568',
                        'name' => 'points2',
                    ],
                    '1234567' => [
                        'id' => '1234567',
                        'name' => 'points',
                    ],
                ],
            ],
            [
                'id' => '123456',
                'bar' => 'foo',
                'points' => [
                    '1234568' => [
                        'id' => '1234568',
                        'name' => 'points2',
                    ],
                ],
            ],
        ];
    }
}
