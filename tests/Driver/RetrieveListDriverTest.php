<?php

namespace App\Tests\Driver;

use App\Driver\EntityJar;
use App\Driver\PointDriver;
use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RetrieveListDriverTest extends TodolistDriverTest
{
    private ?TodoListDriver $driver;

    public function setUp(): void
    {
        parent::setUp();

        [
            $id,
            $entity
        ] = $this->getProvidedData();

        $this->driver = new TodoListDriver(self::$listDir, new PointDriver());
        $this->driver->setNormalizer(new JsonNormalizer());
        $this->driver->setEntityJar(new EntityJar());
        $this->driver->create($entity);
    }

    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $expected
     */
    public function testRetrieve(string $id, array $expected): void
    {
        $this->todoList = $this->driver->retrieve($id);

        self::assertEquals($expected['id'], $this->todoList['id']);
        self::assertEquals($expected['foo'], $this->todoList['foo']);
    }

    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $expected
     */
    public function testNotFound(string $id, array $expected): void
    {
        $this->todoList = $expected;
        self::expectException(NotFoundHttpException::class);
        $this->driver->retrieve('foo');
    }

    /**
     * @return \Generator<int, string|array<string|int, mixed>>
     */
    public function getCase(): \Generator
    {
        yield [
            '123456',
            [
                'id' => '123456',
                'foo' => 'bar',
            ],
        ];
    }
}
