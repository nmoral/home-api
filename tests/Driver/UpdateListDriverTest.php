<?php

namespace App\Tests\Driver;

use App\Driver\EntityJar;
use App\Driver\PointDriver;
use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;

class UpdateListDriverTest extends TodolistDriverTest
{
    private ?TodoListDriver $driver;

    public function setUp(): void
    {
        parent::setUp();

        [
            $entity,
            $id,
            $expected,
            $existent,
        ] = $this->getProvidedData();

        $this->driver = new TodoListDriver(self::$listDir, new PointDriver());
        $this->driver->setNormalizer(new JsonNormalizer());
        $this->driver->setEntityJar(new EntityJar());
        $this->driver->create($existent);
    }

    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $entity
     * @param array<mixed> $expected
     */
    public function testUpdate(string $id, array $entity, array $expected): void
    {
        $this->todoList = $this->driver->update($id, $entity);

        self::assertEquals($expected['id'], $this->todoList['id']);
        self::assertEquals($expected['foo'], $this->todoList['foo']);
    }

    /**
     * @dataProvider getSubEntityCase
     *
     * @param array<mixed> $entity
     * @param array<mixed> $expected
     */
    public function testUpdateSubEntity(string $id, string $pointsId, array $entity, array $expected): void
    {
        $this->todoList = $this->driver->update($id, $entity);

        self::assertEquals($expected['points'][$pointsId]['id'], $this->todoList['points'][$pointsId]['id']);
        self::assertEquals($expected['points'][$pointsId]['foo'], $this->todoList['points'][$pointsId]['foo']);
    }

    /**
     * @return \Generator<int, string|array<string|int, mixed>>
     */
    public function getCase(): \Generator
    {
        yield [
            '123456',
            [
                'foo' => 'bar',
            ],
            [
                'id' => '123456',
                'foo' => 'bar',
            ],
            [
                'id' => '123456',
            ],
        ];
    }

    /**
     * @return \Generator<int, string|array<string|int, mixed>>
     */
    public function getSubEntityCase(): \Generator
    {
        yield [
            '123456',
            'Ab234567',
            [
                'points' => [
                    'Ab234567' => [
                        'id' => 'Ab234567',
                        'foo' => 'bar',
                    ],
                ],
            ],
            [
                'id' => '123456',
                'points' => [
                    'Ab234567' => [
                        'id' => 'Ab234567',
                        'foo' => 'bar',
                    ],
                ],
            ],
            [
                'id' => '123456',
                'points' => [
                    'Ab234567' => [
                        'id' => 'Ab234567',
                    ],
                ],
            ],
        ];
    }
}
