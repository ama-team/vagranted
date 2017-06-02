<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Filesystem;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Filesystem\FilePatternLocator;
use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\FilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\RenamingFilePatternInterface;
use AmaTeam\Vagranted\Tests\Support\Test;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use SplFileInfo;
use Twig_Environment;
use Twig_Loader_Array;

/**
 * @author Etki <etki@etki.me>
 */
class FilePatternLocatorTest extends Test
{
    /**
     * @var FilePatternLocator
     */
    private $locator;

    /**
     * @var AccessorInterface|Mock
     */
    private $filesystem;

    /**
     * @var Twig_Environment|Mock
     */
    private $twig;

    protected function _before()
    {
        $this->filesystem = $this->createMock(AccessorInterface::class);
        $this->twig = new Twig_Environment(new Twig_Loader_Array([]));
        $this->locator = new FilePatternLocator($this->filesystem, $this->twig);
    }

    private function path($path)
    {
        return Path::parse($path);
    }

    private function results(array $paths)
    {
        return array_map(function ($path) {
            $pathname = $this->path($path);
            $mock = $this->createMock(SplFileInfo::class);
            $mock
                ->expects($this->any())
                ->method('getPathname')
                ->willReturn($pathname);
            return $mock;
        }, $paths);
    }

    private function normalize(array $results)
    {
        $paths = [];
        foreach ($results as $key => $value) {
            $paths[(string) $this->path($key)] = (string) $this->path($value);
        }
        return $paths;
    }

    private function normalizeMany(array $results)
    {
        $paths = [];
        foreach ($results as $key => $values) {
            $paths[(string) $this->path($key)] = array_map(function ($value) {
                return (string) $this->path($value);
            }, $values);
        }
        return $paths;
    }

    private function createPattern(
        $pattern,
        array $exclusions = null,
        $name = null
    ) {
        $interfaces = [];
        if ($exclusions !== null) {
            $interfaces[] = ExclusiveFilePatternInterface::class;
        }
        if ($name) {
            $interfaces[] = RenamingFilePatternInterface::class;
        }
        $interfaces = $interfaces ?: [FilePatternInterface::class];
        $interfaces = sizeof($interfaces) === 1 ? current($interfaces) : $interfaces;
        $mock = $this->createMock($interfaces);
        $mock->method('getPattern')->willReturn($pattern);
        if ($exclusions !== null) {
            $mock->method('getExclusions')->willReturn($exclusions);
        }
        if ($name) {
            $mock->method('getName')->willReturn($name);
        }
        return $mock;
    }

    public function simplePatternProvider()
    {
        return [
            [
                $this->path('/'),
                $this->results(['/alpha', '/beta', '/gamma']),
                $this->createPattern('*'),
                $this->normalize([
                    'alpha' => 'alpha',
                    'beta' => 'beta',
                    'gamma' => 'gamma',
                ]),
            ],
            [
                $this->path('/'),
                $this->results(['/alpha', '/beta/gamma', '/delta/omega/epsilon']),
                $this->createPattern($this->path('*/**')),
                $this->normalize([
                    'beta/gamma' => 'beta/gamma',
                    'delta/omega/epsilon' => 'delta/omega/epsilon',
                ]),
            ],
            [
                $this->path('/'),
                $this->results(['/alpha/beta/gamma', '/alumni/paspartu', '/omega']),
                $this->createPattern('al*'),
                $this->normalize([
                    'alpha/beta/gamma' =>'alpha/beta/gamma',
                    'alumni/paspartu' => 'alumni/paspartu',
                ]),
            ]
        ];
    }

    public function exclusivePatternProvider()
    {
        return [
            [
                $this->path('/'),
                $this->results(['/alpha', '/alumni', '/albuquerque']),
                $this->createPattern('al*', ['*que*', '*ni']),
                [
                    'alpha' => 'alpha',
                ],
            ],
            [
                $this->path('/'),
                $this->results(['/alpha', '/alumni', '/albuquerque']),
                $this->createPattern('al*', ['al*']),
                [],
            ],
        ];
    }

    public function renamingPatternProvider()
    {
        return [
            [
                $this->path('/'),
                $this->results(['/alpha.YML', '/beta.yaml', '/gamma.yml']),
                $this->createPattern('*', null, '{{basename}}.{{extension|lower}}.dist'),
                [
                    'alpha.YML' => 'alpha.yml.dist',
                    'beta.yaml' => 'beta.yaml.dist',
                    'gamma.yml' => 'gamma.yml.dist',
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider simplePatternProvider
     *
     * @param $path
     * @param string[] $paths
     * @param $pattern
     * @param string[] $expected
     */
    public function shouldMatchSimplePattern(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $results = $this->normalize($this->locator->locate($path, $pattern));
        $this->assertEquals($expected, $results);
    }

    /**
     * @test
     *
     * @dataProvider exclusivePatternProvider
     *
     * @param $path
     * @param string[] $paths
     * @param $pattern
     * @param string[] $expected
     */
    public function shouldFilterExclusions(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $results = $this->normalize($this->locator->locate($path, $pattern));
        $this->assertEquals($expected, $results);
    }

    /**
     * @test
     *
     * @dataProvider renamingPatternProvider
     *
     * @param $path
     * @param string[] $paths
     * @param $pattern
     * @param string[] $expected
     */
    public function shouldRenameMatches(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $results = $this->normalize($this->locator->locate($path, $pattern));
        $this->assertEquals($expected, $results);
    }

    /**
     * @test
     */
    public function shouldReturnExpectedResultsOnLocateMany()
    {
        $patterns = [
            $this->createPattern('**/*.yml', ['vagranted.yml']),
            $this->createPattern('resources/docker/**', null, 'docker/{{ directory }}/{{ filename }}')
        ];
        $paths = [
            '/vagranted.yml',
            '/resources/docker/Dockerfile',
            '/resources/docker/etc/app.yml',
            '/resources/chef/data_bags/users.yml'
        ];
        $this->filesystem->method('enumerate')->willReturn($this->results($paths));
        $expectations = [
            'resources/docker/etc/app.yml' => [
                'resources/docker/etc/app.yml',
                'docker/resources/docker/etc/app.yml',
            ],
            'resources/chef/data_bags/users.yml' => ['resources/chef/data_bags/users.yml'],
            'resources/docker/Dockerfile' => ['docker/resources/docker/Dockerfile']
        ];
        $results = $this->normalizeMany($this->locator->locateMany($this->path('/'), $patterns));
        $this->assertEquals($expectations, $results);
    }
}
