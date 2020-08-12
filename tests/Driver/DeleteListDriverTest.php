<?php

namespace App\Tests\Driver;

use App\Driver\EntityJar;
use App\Driver\PointDriver;
use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteListDriverTest extends TodolistDriverTest
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
    protected function tearDown(): void
    {
        self::removeTodolistDir();
    }

    /**
     * @dataProvider getCase
     */
    public function testDelete(string $id): void
    {
        $actual = $this->driver->delete($id);

        self::assertTrue($actual);
    }

    /**
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
