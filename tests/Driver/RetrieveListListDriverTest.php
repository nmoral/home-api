<?php

namespace App\Tests\Driver;

use App\Driver\EntityJar;
use App\Driver\PointDriver;
use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RetrieveListListDriverTest extends TodolistDriverTest
{
    private ?TodoListDriver $driver;

    public function setUp(): void
    {
        parent::setUp();

        [
            $count,
            $entities
        ] = $this->getProvidedData();

        $this->driver = new TodoListDriver(self::$listDir, new PointDriver());
        $this->driver->setNormalizer(new JsonNormalizer());
        $this->driver->setEntityJar(new EntityJar());
        foreach ($entities as $entity) {
            $this->driver->create($entity);
        }
    }
    protected function tearDown(): void
    {
        if (null === $this->todoList) {
            return;
        }
        foreach ($this->todoList as $list) {
            $this->removeTodolistRecord($list['id']);
        }
        self::removeTodolistDir();
        $this->todoList = null;
    }

    /**
     * @dataProvider getCase
     */
    public function testRetrieveList(int $expectedCount): void
    {
        $this->todoList = $this->driver->retrieveList();

        self::assertCount($expectedCount, $this->todoList);
    }

    /**
    /**
     * @return \Generator<int, string|array<string|int, mixed>>
     */
    public function getCase(): \Generator
    {
        yield [
            2,
            [
              [
                  'id' => '123456',
                  'foo' => 'bar',
              ],
              [
                  'id' => '123457',
                  'foo' => 'bar',
              ]
            ],
        ];
    }
}
