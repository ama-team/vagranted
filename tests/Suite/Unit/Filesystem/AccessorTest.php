<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Filesystem;

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

    /**
     * @test
     */
    public function shouldSetAndReadFileWithNestedPath()
    {
        $path = $this->vfs->path('/node/leaf');
        $contents = 'data' . PHP_EOL;
        $this->accessor->set($path, $contents);
        $this->assertEquals($contents, $this->accessor->get($path));
    }

    /**
     * @test
     */
    public function shouldMirrorDirectory()
    {
        $nodePath = $this->vfs->path('/node');
        $targetPath = $this->vfs->path('/target/node');
        $leaves = [
            ['path' => 'leaf-a', 'contents' => 'leaf-a' . PHP_EOL,],
            ['path' => 'node/leaf-b', 'contents' => 'leaf-b' . PHP_EOL,],
        ];
        foreach ($leaves as $leaf) {
            $path = $this->vfs->path('/node/' . $leaf['path']);
            $this->accessor->set($path, $leaf['contents']);
        }
        $this->accessor->copy($nodePath, $targetPath);

        foreach ($leaves as $leaf) {
            $path = $this->vfs->path('/target/node/' . $leaf['path']);
            $this->assertEquals($leaf['contents'], $this->accessor->get($path));
        }
    }

    /**
     * @test
     */
    public function shouldMirrorFile()
    {
        $source = $this->vfs->path('/node/leaf');
        $target = $this->vfs->path('/target/leaf');
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
        $path = $this->vfs->path('/node');
        $this->assertTrue($this->accessor->createDirectory($path));
        $this->assertFalse($this->accessor->createDirectory($path));
    }

    /**
     * @test
     */
    public function shouldIdempotentlyDeleteFile()
    {
        $path = $this->vfs->path('/node/leaf');
        $this->accessor->set($path, 'leaf' . PHP_EOL);
        $this->assertTrue($this->accessor->delete($path));
        $this->assertFalse($this->accessor->delete($path));
    }

    /**
     * @test
     */
    public function shouldIdempotentlyDeleteDirectory()
    {
        $path = $this->vfs->path('/node');
        $this->accessor->createDirectory($path);
        $this->assertTrue($this->accessor->delete($path));
        $this->assertFalse($this->accessor->delete($path));
    }

    /**
     * @test
     */
    public function shouldCorrectlyHandleInspectCall()
    {
        $path = $this->vfs->path('/node');
        $this->assertNull($this->accessor->inspect($path));
        $this->accessor->createDirectory($path);
        $this->assertInstanceOf(SplFileInfo::class, $this->accessor->inspect($path));
    }

    /**
     * @test
     */
    public function shouldReturnContentsOnEnumerateCall()
    {
        $path = $this->vfs->path('/node');
        $leaves = ['leaf-a', 'leaf-b', 'node/leaf-c',];
        $expected = ['leaf-a', 'leaf-b', 'node',];
        foreach ($leaves as &$leaf) {
            $leaf = $this->vfs->path("/node/$leaf");
            $this->accessor->set($leaf, '');
        }
        foreach ($expected as &$expectation) {
            $expectation = $this->vfs->path("/node/$expectation");
        }
        $this->accessor->set($this->vfs->path('/node/node/leaf-c'), '');
        $results = [];
        /** @var SplFileInfo $result */
        foreach ($this->accessor->enumerate($path, false) as $result) {
            $results[] = $result->getPathname();
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
        $path = $this->vfs->path('/node');
        $leaves = ['leaf-a', 'leaf-b', 'node/leaf-c',];
        $expected = ['leaf-a', 'leaf-b', 'node', 'node/leaf-c',];
        foreach ($leaves as &$leaf) {
            $leaf = $this->vfs->path("/node/$leaf");
            $this->accessor->set($leaf, '');
        }
        foreach ($expected as &$expectation) {
            $expectation = $this->vfs->path("/node/$expectation");
        }
        $results = [];
        /** @var SplFileInfo $result */
        foreach ($this->accessor->enumerate($path, true) as $result) {
            $results[] = $result->getPathname();
        }
        sort($expected);
        sort($results);
        $this->assertEquals($expected, $results);
    }
}
