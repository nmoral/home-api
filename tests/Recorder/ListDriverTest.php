<?php

namespace App\Tests\Recorder;

use App\Recorder\PointDriver;
use App\Recorder\RecordDriver;
use App\Recorder\TodoListDriver;
use PHPUnit\Framework\TestCase;

class ListDriverTest extends TestCase
{
    private static ?string $listDir = null;

    /**
     * @var array<mixed>|null
     */
    private ?array $todoList = null;

    /**
     * @return mixed
     */
    private static function getRootDir()
    {
        return $_SERVER['TRAVIS_BUILD_DIR'] ?? $_ENV['PWD'];
    }

    protected function tearDown(): void
    {
        if (null === $this->todoList) {
            return;
        }
        $this->removeTodolistRecord($this->todoList['id']);
        self::removeTodolistDir();
        $this->todoList = null;
    }

    public static function setUpBeforeClass(): void
    {
        self::removeTodolistAndRootDir();
    }

    public static function tearDownAfterClass(): void
    {
        self::removeTodolistAndRootDir();
    }

    /**
     * @dataProvider getCase
     *
     * @param array<mixed> $todoList
     */
    public function testSave(array $todoList): void
    {
        $recorder = new TodoListDriver(self::$listDir, new PointDriver());

        $this->todoList = $recorder->save($todoList);

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

        $this->todoList = $recorder->save($todoList);

        self::assertInstanceOf(\DateTime::class, $this->todoList['createdAt']);
    }

    public function testExpectInvalidArgumentWhenNoRecordDir(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $record = new class() extends RecordDriver {
        };
        $record->save([]);
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

    private function removeTodolistRecord(string $id): void
    {
        unlink(self::$listDir.'/'.$id);
    }

    private static function removeDir(string $dir): void
    {
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    private static function removeRootDir(): void
    {
        $rootDir = self::getRootDir().'/'.$_SERVER['TODO_LIST_DIR'];
        self::removeDir($rootDir);
    }

    private static function removeTodolistDir(): void
    {
        self::$listDir = sprintf('%s/%s/%s', self::getRootDir(), $_SERVER['FILES_DIR'], $_SERVER['TODO_LIST_DIR']);
        self::removeDir(self::$listDir);
    }

    private static function removeTodolistAndRootDir(): void
    {
        self::removeTodolistDir();
        self::removeRootDir();
    }
}
