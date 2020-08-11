<?php

namespace App\Tests\Driver;

use PHPUnit\Framework\TestCase;

abstract class TodolistDriverTest extends TestCase
{
    protected static ?string $listDir = null;

    /**
     * @var array<mixed>|null
     */
    protected ?array $todoList = null;

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
