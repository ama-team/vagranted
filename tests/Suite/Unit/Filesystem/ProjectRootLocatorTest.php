<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Filesystem\ProjectRootLocator;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * @author Etki <etki@etki.me>
 */
class ProjectRootLocatorTest extends Unit
{
    /**
     * @var AccessorInterface|Mock
     */
    private $filesystem;

    /**
     * @var ProjectRootLocator
     */
    private $locator;

    protected function _before()
    {
        $this->filesystem = $this->createMock(AccessorInterface::class);
        $this->locator = new ProjectRootLocator($this->filesystem);
    }

    public function dataProvider()
    {
        return [
            ['/node/node', ['/node/vagranted.yml'], '/node',],
            [
                '/node/node',
                ['/node/vagranted.yml', '/node/node/vagranted.yml'],
                '/node/node',
            ],
            ['/node/node', [], null,]
        ];
    }

    /**
     * @test
     *
     * @dataProvider dataProvider
     *
     * @param string $cwd
     * @param string[] $paths
     * @param string|null $result
     */
    public function shouldFindExpectedRoot($cwd, $paths, $result)
    {
        $this
            ->filesystem
            ->method('exists')
            ->willReturnCallback(function ($path) use ($paths) {
                return in_array($path, $paths);
            });
        $this->assertEquals($result, $this->locator->locate(Path::parse($cwd)));
    }
}
