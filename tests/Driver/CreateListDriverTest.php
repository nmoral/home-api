<?php

namespace App\Tests\Driver;

use App\Driver\PointDriver;
use App\Driver\RecordDriver;
use App\Driver\TodoListDriver;

class CreateListDriverTest extends TodolistDriverTest
{
    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $todoList
     */
    public function testSave(array $todoList): void
    {
        $recorder = new TodoListDriver(self::$listDir, new PointDriver());

        $this->todoList = $recorder->create($todoList);

        self::assertTrue(file_exists(self::$listDir.'/'.$this->todoList['id']));
    }

    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $todoList
     */
    public function testCreatedAt(array $todoList): void
    {
        $recorder = new TodoListDriver(self::$listDir, new PointDriver());

        $this->todoList = $recorder->create($todoList);

        self::assertInstanceOf(\DateTime::class, $this->todoList['createdAt']);
    }

    /**
     * @dataProvider getProvidedId
     *
     * @param array<mixed> $todolist
     */
    public function testProvidedId(array $todolist, string $expected): void
    {
        $recorder = new TodoListDriver(self::$listDir, new PointDriver());

        $this->todoList = $recorder->create($todolist);
        self::assertEquals($expected, $this->todoList['id']);
    }

    public function testExpectInvalidArgumentWhenNoRecordDir(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $record = new class() extends RecordDriver {
        };
        $record->create([]);
    }

    /**
     * @return \Generator<array<int, mixed>>
     */
    public function getCase(): \Generator
    {
        yield [
            [
                'name' => 'foo',
            ],
        ];
    }

    /**
     * @return \Generator<array<int, mixed>>
     */
    public function getProvidedId(): \Generator
    {
        yield [
            [
                'name' => 'foo',
                'id' => '123456',
            ],
            '123456',
        ];
    }
}
