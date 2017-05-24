<?php

namespace AmaTeam\Vagranted\Tests\Suite\Unit\Filesystem;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Filesystem\PatternLocator;
use AmaTeam\Vagranted\Model\Filesystem\ExclusiveFilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\FilePatternInterface;
use AmaTeam\Vagranted\Model\Filesystem\RenamingFilePatternInterface;
use Codeception\Test\Unit;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Twig_Environment;
use Twig_Loader_Array;

/**
 *
 *
 * @author Etki <etki@etki.me>
 */
class PatternLocatorTest extends Unit
{
    /**
     * @var PatternLocator
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

    public function _before()
    {
        $this->filesystem = $this->createMock(AccessorInterface::class);
        $this->twig = new Twig_Environment(new Twig_Loader_Array([]));
        $this->locator = new PatternLocator($this->filesystem, $this->twig);
    }

    private function path($path)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return $path;
        }
        $path = str_replace('/', '\\', $path);
        if (strlen($path) > 0 && $path[0] === '\\') {
            $path = 'c:' . $path;
        }
        return $path;
    }

    private function paths(array $paths)
    {
        return array_map([$this, 'path'], $paths);
    }

    private function normalize(array $results)
    {
        $paths = [];
        foreach ($results as $key => $value)
        {
            $paths[$this->path($key)] = $this->path($value);
        }
        return $paths;
    }

    public function simplePatternProvider()
    {
        return [
            [
                $this->path('/'),
                $this->paths(['/alpha', '/beta', '/gamma']),
                $this->createPattern('*'),
                $this->normalize([
                    'alpha' => 'alpha',
                    'beta' => 'beta',
                    'gamma' => 'gamma',
                ]),
            ],
            [
                $this->path('/'),
                $this->paths(['/alpha', '/beta/gamma', '/delta/omega/epsilon']),
                $this->createPattern($this->path('*/**')),
                $this->normalize([
                    'beta/gamma' => 'beta/gamma',
                    'delta/omega/epsilon' => 'delta/omega/epsilon',
                ]),
            ],
            [
                $this->path('/'),
                $this->paths(['/alpha/beta/gamma', '/alumni/paspartu', '/omega']),
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
                $this->paths(['/alpha', '/alumni', '/albuquerque']),
                $this->createPattern('al*', ['*que*', '*ni']),
                [
                    'alpha' => 'alpha',
                ],
            ],
            [
                $this->path('/'),
                $this->paths(['/alpha', '/alumni', '/albuquerque']),
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
                $this->paths(['/alpha.YML', '/beta.yaml', '/gamma.yml']),
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
}
