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

    public function simplePatternProvider()
    {
        return [
            [
                '/',
                ['/alpha', '/beta', '/gamma'],
                $this->createPattern('*'),
                [
                    'alpha' => 'alpha',
                    'beta' => 'beta',
                    'gamma' => 'gamma',
                ],
            ],
            [
                '/',
                ['/alpha', '/beta/gamma', '/delta/omega/epsilon'],
                $this->createPattern('*/**'),
                [
                    'beta/gamma' => 'beta/gamma',
                    'delta/omega/epsilon' => 'delta/omega/epsilon',
                ],
            ],
            [
                '/',
                ['/alpha/beta/gamma', '/alumni/paspartu', '/omega'],
                $this->createPattern('al*'),
                [
                    'alpha/beta/gamma' =>'alpha/beta/gamma',
                    'alumni/paspartu' => 'alumni/paspartu',
                ],
            ]
        ];
    }

    public function exclusivePatternProvider()
    {
        return [
            [
                '/',
                ['/alpha', '/alumni', '/albuquerque'],
                $this->createPattern('al*', ['*que*', '*ni']),
                [
                    'alpha' => 'alpha',
                ],
            ],
            [
                '/',
                ['/alpha', '/alumni', '/albuquerque'],
                $this->createPattern('al*', ['al*']),
                [],
            ],
        ];
    }

    public function renamingPatternProvider()
    {
        return [
            [
                '/',
                ['/alpha.YML', '/beta.yaml', '/gamma.yml'],
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
     * @param array $expected
     */
    public function shouldMatchSimplePattern(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $this->assertEquals($expected, $this->locator->locate($path, $pattern));
    }

    /**
     * @test
     *
     * @dataProvider exclusivePatternProvider
     *
     * @param $path
     * @param string[] $paths
     * @param $pattern
     * @param array $expected
     */
    public function shouldFilterExclusions(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $this->assertEquals($expected, $this->locator->locate($path, $pattern));
    }

    /**
     * @test
     *
     * @dataProvider renamingPatternProvider
     *
     * @param $path
     * @param string[] $paths
     * @param $pattern
     * @param array $expected
     */
    public function shouldRenameMatches(
        $path,
        array $paths,
        $pattern,
        array $expected
    ) {
        $this->filesystem->method('enumerate')->willReturn($paths);
        $this->assertEquals($expected, $this->locator->locate($path, $pattern));
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
