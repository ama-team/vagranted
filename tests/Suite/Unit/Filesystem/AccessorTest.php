<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Filesystem\Accessor;
use Codeception\Test\Unit;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use VirtualFileSystem\FileSystem as VFS;

/**
 * @author Etki <etki@etki.me>
 */
class AccessorTest extends Unit
{
    /**
     * @var VFS
     */
    private $vfs;

    /**
     * @var Accessor
     */
    private $accessor;

    public function _before()
    {
        $this->vfs = new VFS();
        $this->accessor = new Accessor(new Filesystem());
    }

    private function path($path)
    {
        return Path::parse($this->vfs->path($path));
    }

    /**
     * @test
     */
    public function shouldSetAndReadFileWithNestedPath()
    {
        $path = $this->path('/node/leaf');
        $contents = 'data' . PHP_EOL;
        $this->accessor->set($path, $contents);
        $this->assertEquals($contents, $this->accessor->get($path));
    }

    /**
     * @test
     */
    public function shouldMirrorDirectory()
    {
        $nodePath = $this->path('/node');
        $targetPath = $this->path('/target/node');
        $leaves = [
            ['path' => 'leaf-a', 'contents' => 'leaf-a' . PHP_EOL,],
            ['path' => 'node/leaf-b', 'contents' => 'leaf-b' . PHP_EOL,],
        ];
        foreach ($leaves as $leaf) {
            $path = $nodePath->resolve($leaf['path']);
            $this->accessor->set($path, $leaf['contents']);
        }
        $this->accessor->copy($nodePath, $targetPath);

        foreach ($leaves as $leaf) {
            $path = $targetPath->resolve($leaf['path']);
            $this->assertEquals($leaf['contents'], $this->accessor->get($path));
        }
    }

    /**
     * @test
     */
    public function shouldMirrorFile()
    {
        $source = $this->path('/node/leaf');
        $target = $this->path('/target/leaf');
        $contents = 'leaf' . PHP_EOL;
        $this->accessor->set($source, $contents);
        $this->accessor->set($target, 'garbage content' . PHP_EOL);
        $this->accessor->copy($source, $target);
        $this->assertEquals($contents, $this->accessor->get($target));
    }

    /**
     * @test
     */
    public function shouldIdempotentlyCreateDirectory()
    {
        $path = $this->path('/node');
        $this->assertTrue($this->accessor->createDirectory($path));
        $this->assertFalse($this->accessor->createDirectory($path));
    }

    /**
     * @test
     */
    public function shouldIdempotentlyDeleteFile()
    {
        $path = $this->path('/node/leaf');
        $this->accessor->set($path, 'leaf' . PHP_EOL);
        $this->assertTrue($this->accessor->delete($path));
        $this->assertFalse($this->accessor->delete($path));
    }

    /**
     * @test
     */
    public function shouldIdempotentlyDeleteDirectory()
    {
        $path = $this->path('/node');
        $this->accessor->createDirectory($path);
        $this->assertTrue($this->accessor->delete($path));
        $this->assertFalse($this->accessor->delete($path));
    }

    /**
     * @test
     */
    public function shouldCorrectlyHandleInspectCall()
    {
        $path = $this->path('/node');
        $this->assertNull($this->accessor->inspect($path));
        $this->accessor->createDirectory($path);
        $this->assertInstanceOf(SplFileInfo::class, $this->accessor->inspect($path));
    }

    /**
     * @test
     */
    public function shouldReturnContentsOnEnumerateCall()
    {
        $path = $this->path('/node');
        $leaves = ['leaf-a', 'leaf-b', 'node/leaf-c',];
        $expected = ['leaf-a', 'leaf-b', 'node',];
        foreach ($leaves as &$leaf) {
            $leaf = $path->resolve($leaf);
            $this->accessor->set($leaf, '');
        }
        foreach ($expected as &$expectation) {
            $expectation = $path->resolve($expectation);
        }
        $this->accessor->set($this->path('/node/node/leaf-c'), '');
        $results = [];
        /** @var SplFileInfo $result */
        foreach ($this->accessor->enumerate($path, false) as $result) {
            $results[] = $this->normalize($result->getPathname());
        }
        sort($leaves);
        sort($results);
        $this->assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function shouldReturnAllContentsOnRecursiveEnumerateCall()
    {
        $path = $this->path('/node');
        $leaves = ['leaf-a', 'leaf-b', 'node/leaf-c',];
        $expected = ['leaf-a', 'leaf-b', 'node', 'node/leaf-c',];
        foreach ($leaves as &$leaf) {
            $leaf = $path->resolve($leaf);
            $this->accessor->set($leaf, '');
        }
        foreach ($expected as &$expectation) {
            $expectation = $path->resolve($expectation);
        }
        $results = [];
        /** @var SplFileInfo $result */
        foreach ($this->accessor->enumerate($path, true) as $result) {
            $results[] = $this->normalize($result->getPathname());
        }
        sort($expected);
        sort($results);
        $this->assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function shouldIncludeDirectoriesInEnumeration()
    {
        $path = $this->path('/node');
        $directoryA = $this->path('/node/node-a');
        $directoryB = $this->path('/node/node-b');
        $directoryC = $this->path('/node/node-b/node-c');
        $this->accessor->createDirectory($directoryA);
        $this->accessor->createDirectory($directoryC);
        $iterator = $this->accessor->enumerate($path);
        $results = [];
        /** @var SplFileInfo $info */
        foreach ($iterator as $info) {
            $results[] = $this->normalize($info->getPathname());
        }
        $expectation = [$directoryA, $directoryB,];
        sort($results);
        sort($expectation);
        $this->assertEquals($expectation, $results);
    }

    private function normalize($path)
    {
        return str_replace('\\', '/', $path);
    }

    private function normalizeMany(array $paths) {
        return array_map([$this, 'normalize',], $paths);
    }
}
